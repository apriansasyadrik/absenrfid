<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * WaQueue API Controller
 * Process WhatsApp queue (to be run as cron job)
 */
class WaQueue extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Wa_model');
    }

    /**
     * Process pending WhatsApp queue
     * Run this via cron: * * * * * php /path/to/index.php api/waqueue/process
     */
    public function process() {
        // Get pending messages
        $queue = $this->Wa_model->get_pending_queue(10);
        
        $processed = 0;
        $sent = 0;
        $failed = 0;
        
        foreach ($queue as $item) {
            $processed++;
            
            // Send message
            $result = $this->Wa_model->send_message($item['no_hp'], $item['pesan']);
            
            if ($result['status']) {
                // Success
                $this->Wa_model->update_queue_status($item['id'], 'sent', $result['response']);
                $sent++;
            } else {
                // Failed - increment attempt
                $this->Wa_model->increment_attempt($item['id']);
                
                // Check if max attempts reached
                if ($item['attempt'] + 1 >= $item['max_attempt']) {
                    $this->Wa_model->update_queue_status($item['id'], 'failed', $result['message']);
                }
                $failed++;
            }
            
            // Small delay to avoid rate limiting
            usleep(500000); // 0.5 seconds
        }
        
        // Log result
        $message = sprintf(
            'WhatsApp Queue Processed: %d total, %d sent, %d failed',
            $processed,
            $sent,
            $failed
        );
        
        log_message('info', $message);
        
        // Return JSON for monitoring
        json_response(true, $message, array(
            'processed' => $processed,
            'sent' => $sent,
            'failed' => $failed
        ));
    }
}
