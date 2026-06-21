function toggleSidebar() {
  document.getElementById("samping").classList.toggle("buka");
}

function piliUkuran(event, chip) {
  chip.classList.toggle("aktif");
  var cb = chip.parentElement.querySelector('input[type="checkbox"]');
  if (cb) cb.checked = chip.classList.contains("aktif");
  muat();
}
