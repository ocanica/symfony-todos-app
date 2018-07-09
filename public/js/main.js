const todo = document.getElementById('todo');

if (todo) {
  todo.addEventListener('click', e => {
    if (e.target.className === 'btn btn-danger delete-todo') {
      if (confirm('Are you sure?')) {
        const id = e.target.getAttribute('data-id');

        fetch(`delete/${id}`, {
          method: 'DELETE'
        }).then(
          res => window.location.reload()
        );
      }
    }
  });
}