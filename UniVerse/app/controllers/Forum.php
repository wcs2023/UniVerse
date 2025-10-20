<?php
class Forum extends Controller
{
  private function requireLogin() {
    if (empty($_SESSION['USER'])) {
      redirect(BASE_URL . '/login');
      exit;
    }
  }

  private function isOwnerOrAdmin($ownerId) {
    $u = $_SESSION['USER'] ?? null;
    if (!$u) return false;
    return ((int)$u->id === (int)$ownerId) || !empty($u->is_admin);
  }

  // ... all methods: index, category, thread, create, reply, vote, edit_thread, edit_post, delete_thread, delete_post ...
}
