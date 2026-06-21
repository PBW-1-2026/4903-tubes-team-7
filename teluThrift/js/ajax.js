function formatHarga(angka) {
  return "Rp " + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function kondisiText(kondisi) {
  return kondisi.replace(/_/g, " ").replace(/\b\w/g, function (l) {
    return l.toUpperCase();
  });
}

function buatCard(p, sudahLogin, isAdmin) {
  var kelas = "kartu-produk";
  if (p.status === "terjual") kelas += " terjual";

  var gambar = "";
  if (p.gambar) {
    gambar =
      '<img src="uploads/' +
      p.gambar +
      '" alt="' +
      p.nama +
      '" loading="lazy">';
  }

  var tagTerjual = "";
  if (p.status === "terjual") {
    tagTerjual = '<span class="label-produk terjual">Terjual</span>';
  }

  var wishlistBtn = "";
  if (sudahLogin) {
    var ikonWish = "";
    if (p.is_wishlisted) {
      ikonWish = '<img src="icon/wishlist.svg" class="ikon-wish">';
    } else {
      ikonWish = '<img src="icon/wish.svg" class="ikon-wish">';
    }
    var aktif = "";
    if (p.is_wishlisted) {
      aktif = " aktif";
    }
    wishlistBtn =
      '<button type="button" class="tombol-favorit' +
      aktif +
      '" onclick="toggleWishlist(event, ' +
      p.id +
      ', this)">' +
      ikonWish +
      "</button>";
  }

  var brand = "—";
  if (p.brand) {
    brand = p.brand;
  }

  var coret = "";
  if (p.status === "terjual") {
    coret = " coret";
  }

  return (
    '<a href="detail.php?id=' +
    p.id +
    '" class="' +
    kelas +
    '">' +
    '<div class="thumb-produk">' +
    gambar +
    tagTerjual +
    wishlistBtn +
    "</div>" +
    '<div class="badan-produk">' +
    '<div class="merek-produk">' +
    brand +
    "</div>" +
    '<div class="nama-produk">' +
    p.nama +
    "</div>" +
    '<div class="harga-produk' +
    coret +
    '">' +
    formatHarga(p.harga) +
    "</div>" +
    '<div class="meta-produk">' +
    '<span class="ukuran-produk">' +
    p.ukuran +
    "</span>" +
    '<span class="dot-kondisi ' +
    p.kondisi +
    '"></span>' +
    '<span class="kondisi-produk">' +
    kondisiText(p.kondisi) +
    "</span>" +
    "</div></div></a>"
  );
}

function buatPagination(page, totalPage) {
  if (totalPage <= 1) return "";

  var html = '<div class="nomor-halaman">';

  var prevClass = "tombol-halaman";
  if (page <= 1) prevClass += " nonaktif";
  html +=
    '<button class="' +
    prevClass +
    '" onclick="gantiHalaman(' +
    (page - 1) +
    ')">‹</button>';

  var start = Math.max(1, page - 2);
  var end = Math.min(totalPage, page + 2);

  for (var i = start; i <= end; i++) {
    var btnClass = "tombol-halaman";
    if (i === page) btnClass += " aktif";
    html +=
      '<button class="' +
      btnClass +
      '" onclick="gantiHalaman(' +
      i +
      ')">' +
      i +
      "</button>";
  }

  var nextClass = "tombol-halaman";
  if (page >= totalPage) nextClass += " nonaktif";
  html +=
    '<button class="' +
    nextClass +
    '" onclick="gantiHalaman(' +
    (page + 1) +
    ')">›</button>';

  html += "</div>";
  return html;
}

function muat(page) {
  if (!page) page = 1;

  var q = document.getElementById("inputCari");
  var kategori = document.querySelector('input[name="kategori"]:checked');
  var sort = document.querySelector('input[name="sort"]:checked');
  var gender = document.getElementById("inputGender");

  var params = [];
  if (q && q.value) params.push("q=" + encodeURIComponent(q.value));
  if (kategori && kategori.value)
    params.push("kategori=" + encodeURIComponent(kategori.value));
  if (sort && sort.value) params.push("sort=" + encodeURIComponent(sort.value));
  if (gender && gender.value)
    params.push("gender=" + encodeURIComponent(gender.value));
  if (page > 1) params.push("page=" + page);

  document
    .querySelectorAll('input[name="kondisi"]:checked')
    .forEach(function (el) {
      params.push("kondisi[]=" + encodeURIComponent(el.value));
    });

  document
    .querySelectorAll('input[name="ukuran"]:checked')
    .forEach(function (el) {
      params.push("ukuran[]=" + encodeURIComponent(el.value));
    });

  var hargaMin = document.getElementById("hargaMin");
  var hargaMax = document.getElementById("hargaMax");
  if (hargaMin && hargaMin.value) {
    params.push("harga_min=" + encodeURIComponent(hargaMin.value));
  }
  if (hargaMax && hargaMax.value) {
    params.push("harga_max=" + encodeURIComponent(hargaMax.value));
  }

  var sale = false;
  if (typeof filterSale !== "undefined" && filterSale) {
    sale = true;
  }
  if (sale) {
    params.push("sale=1");
  }

  document.getElementById("hasilProduk").innerHTML =
    '<div class="memuat"><div class="pemutar"></div>Memuat produk...</div>';

  var xhr = new XMLHttpRequest();
  xhr.onload = function () {
    if (xhr.status === 200) {
      try {
        var res = JSON.parse(xhr.responseText);
        renderProduk(res);
      } catch (e) {
        document.getElementById("hasilProduk").innerHTML =
          '<div style="text-align:center;padding:2rem;color:red;">Gagal memuat data</div>';
      }
    }
  };
  xhr.open("GET", "get_produk.php?" + params.join("&"), true);
  xhr.send();
}

function renderProduk(res) {
  var sudahLogin = res.sudah_login;

  var sortRadio = document.querySelector('input[name="sort"]:checked');
  var sortVal = "terbaru";
  if (sortRadio) {
    sortVal = sortRadio.value;
  }

  var selectedTerbaru = "";
  var selectedTermurah = "";
  var selectedTermahal = "";
  if (sortVal === "terbaru") {
    selectedTerbaru = " selected";
  } else if (sortVal === "termurah") {
    selectedTermurah = " selected";
  } else if (sortVal === "termahal") {
    selectedTermahal = " selected";
  }

  var sortBar =
    '<div class="bar-urut">' +
    '<div class="jumlah-hasil"><strong>' +
    res.total.toLocaleString("id-ID") +
    "</strong> produk ditemukan</div>" +
    '<div class="kanan-urut">' +
    '<select class="pilih-urut" onchange="gantiSort(this.value)">' +
    '<option value="terbaru"' +
    selectedTerbaru +
    ">Terbaru</option>" +
    '<option value="termurah"' +
    selectedTermurah +
    ">Termurah</option>" +
    '<option value="termahal"' +
    selectedTermahal +
    ">Termahal</option>" +
    "</select></div></div>";

  if (res.products.length === 0) {
    document.getElementById("hasilProduk").innerHTML =
      sortBar +
      '<div class="grid-produk">' +
      '<div style="grid-column:1/-1;text-align:center;padding:3rem 0;color:#aaa;">' +
      '<div style="margin-bottom:10px;"><img src="icon/search.svg" alt="cari" style="width:44px;height:44px;display:block;margin:0 auto;opacity:0.3;"></div>' +
      '<p style="font-weight:600;color:#888;margin-bottom:4px;">Produk tidak ditemukan</p>' +
      '<p style="font-size:12px;">Coba kata kunci atau filter yang berbeda</p>' +
      '<button onclick="resetFilter()" class="tombol tombol-garis tombol-kecil" style="margin-top:1rem;">Reset pencarian</button>' +
      "</div></div>";
    return;
  }

  var grid = '<div class="grid-produk">';
  for (var i = 0; i < res.products.length; i++) {
    grid += buatCard(res.products[i], sudahLogin, res.is_admin);
  }
  grid += "</div>";

  var paging = buatPagination(res.page, res.total_page);

  document.getElementById("hasilProduk").innerHTML = sortBar + grid + paging;
}

function gantiHalaman(page) {
  muat(page);
  document
    .getElementById("hasilProduk")
    .scrollIntoView({ behavior: "smooth", block: "start" });
}

function gantiSort(val) {
  document.querySelector('input[name="sort"][value="' + val + '"]').checked =
    true;
  muat();
}

function gantiKategori(val, el) {
  if (val !== "") {
    document.getElementById("inputGender").value = "";
  }
  document.querySelectorAll(".pil-kategori").forEach(function (p) {
    p.classList.remove("aktif");
  });
  el.classList.add("aktif");
  var radio = document.querySelector(
    'input[name="kategori"][value="' + val + '"]',
  );
  if (radio) radio.checked = true;
  muat();
}

function resetFilter() {
  document.getElementById("inputCari").value = "";
  document.getElementById("inputGender").value = "";
  document.getElementById("hargaMin").value = "";
  document.getElementById("hargaMax").value = "";
  document.querySelectorAll('input[name="kategori"]')[0].checked = true;
  document.querySelectorAll('input[name="sort"]')[0].checked = true;
  document.querySelectorAll('input[name="kondisi"]').forEach(function (el) {
    el.checked = false;
  });
  document.querySelectorAll('input[name="ukuran"]').forEach(function (el) {
    el.checked = false;
  });
  document.querySelectorAll(".item-ukuran").forEach(function (el) {
    el.classList.remove("aktif");
  });
  document.querySelectorAll(".pil-kategori").forEach(function (el) {
    el.classList.remove("aktif");
  });
  document.querySelectorAll(".pil-kategori")[0].classList.add("aktif");
  muat();
}

function toggleWishlist(event, produkId, element) {
  event.preventDefault();
  event.stopPropagation();

  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "wishlist.php?produk_id=" + produkId + "&aksi=toggle&ajax=1",
    true,
  );
  xhr.onload = function () {
    if (xhr.status === 200) {
      try {
        var res = JSON.parse(xhr.responseText);
        if (res.status === "success") {
          if (res.is_wishlisted) {
            element.classList.add("aktif");
            element.innerHTML =
              '<img src="icon/wishlist.svg" class="ikon-wish">';
          } else {
            element.classList.remove("aktif");
            element.innerHTML = '<img src="icon/wish.svg" class="ikon-wish">';
          }
        } else if (res.status === "error" && res.message === "not_logged_in") {
          window.location.href = "login.php";
        }
      } catch (e) {}
    }
  };
  xhr.send();
}
