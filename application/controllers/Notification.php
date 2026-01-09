<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in(); // Maintain security check helper
    }

    // AJAX: Get Latest Unread Notifications for Topbar
    public function get_latest()
    {
        $email = $this->session->userdata('email');

        // Count Unread
        $unread_count = $this->db->where('user_email', $email)
            ->where('is_read', 0)
            ->count_all_results('notifications');

        // Get Latest 5 items
        $this->db->where('user_email', $email);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(5);
        $notifications = $this->db->get('notifications')->result_array();

        // Format for JSON
        $data = [];
        foreach ($notifications as $n) {
            $data[] = [
                'id' => $n['id'],
                'title' => $n['title'],
                'message' => strip_tags($n['message']), // Plain text preview
                'link' => $n['link'] ? $n['link'] : '#', // ensure link exists
                'icon' => $n['icon'],
                'color' => $n['color'],
                'is_read' => $n['is_read'],
                'time' => date('M d, Y', strtotime($n['created_at'])) // Format date
            ];
        }

        echo json_encode([
            'count' => $unread_count,
            'notifications' => $data
        ]);
    }

    // AJAX: Mark Notification as Read
    public function mark_read($id)
    {
        $email = $this->session->userdata('email');

        // Security: Ensure the notification belongs to the logged-in user
        $this->db->where('id', $id);
        $this->db->where('user_email', $email);
        $this->db->update('notifications', ['is_read' => 1]);

        echo json_encode(['status' => 'success']);
    }

    // AJAX: Mark All as Read (Optional but good UX)
    public function mark_all_read()
    {
        $email = $this->session->userdata('email');
        $this->db->where('user_email', $email);
        $this->db->update('notifications', ['is_read' => 1]);
        echo json_encode(['status' => 'success']);
    }
}
