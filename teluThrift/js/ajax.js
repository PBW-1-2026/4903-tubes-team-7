function muat() {
    var q = document.querySelector('input[name="q"]');
    var kategori = document.querySelector('input[name="kategori"]:checked');
    var kondisi = document.querySelectorAll('input[name="kondisi"]:checked');
    var sort = document.querySelector('select[name="sort"]');

    var params = [];
    if (q && q.value) params.push('q=' + encodeURIComponent(q.value));
    if (kategori && kategori.value) params.push('kategori=' + encodeURIComponent(kategori.value));
    if (kondisi.length) {
        kondisi.forEach(function(el) { params.push('kondisi[]=' + encodeURIComponent(el.value)); });
    }
    if (sort && sort.value) params.push('sort=' + encodeURIComponent(sort.value));

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById('hasilProduk').innerHTML = xhr.responseText;
        }
    };
    xhr.open('GET', 'get_produk.php?' + params.join('&'), true);
    xhr.send();
}

function toggleSidebar() {
    document.getElementById('samping').classList.toggle('buka');
}
