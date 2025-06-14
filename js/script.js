const modal = document.getElementById('connexionModal');
modal.addEventListener('shown.bs.modal', function () {
  const buttons = document.querySelectorAll('.modal-body a');
  buttons.forEach((button) => {
    button.classList.add('show');
  });
});

modal.addEventListener('hidden.bs.modal', function () {
  const buttons = document.querySelectorAll('.modal-body a');
  buttons.forEach((button) => {
    button.classList.remove('show');
  });
});
