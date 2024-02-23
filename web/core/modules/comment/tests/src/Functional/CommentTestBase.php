/**
 * Performs the specified operation on the specified comment.
 *
 * @param \Drupal\comment\CommentInterface $comment
 *   Comment to perform operation on.
 * @param string $operation
 *   Operation to perform.
 * @param bool $approval
 *   Operation is found on approval page.
 */
public function performCommentOperation(CommentInterface $comment, $operation, $approval = FALSE) {
  $edit = [];
  $edit['operation'] = $operation;
  $edit['comments[' . $comment->id() . ']'] = TRUE;
  $this->drupalPostForm('admin/content/comment' . ($approval ? '/approval' : ''), $edit, t('Update'));

  if ($operation === 'delete') { // Use strict comparison
    $this->drupalPostForm(NULL, [], t('Delete'));
    // Assert that the comment has been deleted.
    $this->assertRaw(\Drupal::translation()->formatPlural(1, 'Deleted 1 comment.', 'Deleted @count comments.'), new FormattableMarkup('Operation "@operation" was performed on comment.', ['@operation' => $operation]));
  } else {
    // Assert that the update has been performed.
    $this->assertText(t('The update has been performed.'), new FormattableMarkup('Operation "@operation" was performed on comment.', ['@operation' => $operation]));
  }
}
