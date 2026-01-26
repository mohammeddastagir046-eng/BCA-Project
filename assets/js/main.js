// Wait until the document is fully loaded
document.addEventListener('DOMContentLoaded', function () {

  // Attach confirmation prompt to all elements with 'confirm-delete' class
  document.querySelectorAll('.confirm-delete').forEach(btn => {
    btn.addEventListener('click', e => {

      // Ask for confirmation before proceeding with delete action
      if (!confirm('Are you sure you want to delete this item?')) {
        e.preventDefault(); // Stop action if user cancels
      }

    });
  });
});
