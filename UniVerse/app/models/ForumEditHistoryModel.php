<?php
class ForumEditHistoryModel extends Model {
  
  protected $table = 'forum_edit_history';

  public function record($type, $entityId, $editorId, $oldTitle, $oldBody) {
    $data = [
      'entity_type'    => $type,
      'entity_id'      => $entityId,
      'editor_user_id' => $editorId,
      'old_title'      => $oldTitle,
      'old_body'       => $oldBody,
    ];
    $keys = array_keys($data);
    $sql = "INSERT INTO {$this->table} (".implode(',', $keys).") VALUES (:".implode(',:', $keys).")";
    $this->query($sql, $data);
  }
}
