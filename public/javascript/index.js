let debounceTimer;

const form = document.getElementById('filterForm');
const searchInput = document.getElementById('searchInput');
const cabangFilter = document.getElementById('cabangFilter');

// Debounce search
if (searchInput)
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            form.submit();
        }, 1000); // 1s delay
    });

// Auto submit saat cabang berubah
if (cabangFilter)
    cabangFilter.addEventListener('change', function () {
        form.submit();
    });

// Fitur Keranjang
const cart = [];
const cabangTambah = document.getElementById('cabangTambah');
const kayuSelectTambah = document.getElementById('kayuSelect');

if (cabangTambah) {
    if (cabangTambah.dataset.role === 'admin') {
        fetchKayu(cabangTambah.dataset.cabang, kayuSelectTambah);
    }

    cabangTambah.addEventListener('change', () => {
        fetchKayu(cabangTambah.value, kayuSelectTambah);
    });
}

function rupiah(num) {
    return 'Rp ' + num.toLocaleString('id-ID');
}

function fetchKayu(cabangId, selectEl) {
    selectEl.innerHTML = '<option value="">Loading...</option>';
    selectEl.disabled = true;

    fetch(`/ajax/kayu-by-cabang/${cabangId}`)
        .then(r => r.json())
        .then(data => {
            selectEl.innerHTML = '<option value="">Pilih Kayu</option>';
            data.forEach(k => {
                selectEl.innerHTML += `
                    <option value="${k.id}"
                        data-nama="${k.jenis_kayu}"
                        data-harga="${k.harga_satuan}">
                        ${k.jenis_kayu} - Rp ${k.harga_satuan.toLocaleString('id-ID')}
                    </option>`;
            });
            selectEl.disabled = false;
        });
}
function renderCartTambah() {
    const list = document.getElementById('cartList');
    let total = 0, info = '', no = 1;

    list.innerHTML = '';
    cart.forEach((item, i) => {
        total += item.subtotal;
        info += `${no}. ${item.nama} (${item.qty} - Rp. ${item.subtotal.toLocaleString('id-ID')})\n`;

        list.innerHTML += `
        <li class="list-group-item d-flex justify-content-between">
            ${item.nama} (${item.qty})
            <button class="btn btn-sm btn-danger" onclick="removeCartTambah(${i})">×</button>
        </li>`;
        no++;
    });

    if (cart.length === 0) {
        list.innerHTML = `<li class="list-group-item text-muted text-center">Belum ada item</li>`;
        cabangTambah.disabled = false;
    } else {
        cabangTambah.disabled = true;
    }

    const hiddenCabang = document.getElementById('cabangHidden');

    if (cart.length > 0) {
        cabangTambah.disabled = true;
        hiddenCabang.value = cabangTambah.value;
    } else {
        cabangTambah.disabled = false;
        hiddenCabang.value = '';
    }


    document.getElementById('informasi').value = info;
    document.getElementById('total').value = total;
    document.getElementById('totalText').innerText = rupiah(total);
}

function removeCartTambah(i) {
    cart.splice(i, 1);
    renderCartTambah();
}


const editCarts = {};

document.querySelectorAll('.cartList').forEach(list => {
    const id = list.dataset.id;
    editCarts[id] = [];

    list.querySelectorAll('li').forEach(li => {
        const txt = li.childNodes[0].nodeValue.trim();
        const m = txt.match(/(.+)\s\((\d+)\s-\sRp\.\s([\d.]+)\)/);
        if (!m) return;

        editCarts[id].push({
            nama: m[1].replace(/^\d+\.\s/, ''),
            qty: +m[2],
            subtotal: +m[3].replace(/\./g, '')
        });
    });

    renderEditCart(id);
});


function renderEditCart(id) {
    const list = document.querySelector(`.cartList[data-id="${id}"]`);
    const modal = list.closest('.modal-content');
    const cabang = modal.querySelector('.cabangEdit');

    let total = 0, info = '', no = 1;
    list.innerHTML = '';

    editCarts[id].forEach((item, i) => {
        total += item.subtotal;
        info += `${no}. ${item.nama} (${item.qty} - Rp. ${item.subtotal.toLocaleString('id-ID')})\n`;

        list.innerHTML += `
        <li class="list-group-item d-flex justify-content-between">
            ${item.nama} (${item.qty})
            <button class="btn btn-sm btn-danger"
                onclick="removeEditItem(${id}, ${i})">×</button>
        </li>`;
        no++;
    });

    if (editCarts[id].length === 0) {
        list.innerHTML = `<li class="list-group-item text-muted text-center">Belum ada item</li>`;

        const kayuSelect = modal.querySelector('.kayuSelect');
        if (cabang.value) {
            fetchKayu(cabang.value, kayuSelect);
        }
    }


    modal.querySelector('.informasi').value = info;
    modal.querySelector('.total').value = total;
    modal.querySelector('.totalText').innerText = rupiah(total);
}

document.querySelectorAll('.cabangEdit').forEach(select => {
    select.addEventListener('change', function () {
        const modal = this.closest('.modal-content');
        const kayuSelect = modal.querySelector('.kayuSelect');

        // reset select
        kayuSelect.innerHTML = '<option value="">Pilih Kayu</option>';
        kayuSelect.disabled = true;

        if (this.value) {
            fetchKayu(this.value, kayuSelect);
        }
    });
});


function removeEditItem(id, i) {
    editCarts[id].splice(i, 1);
    renderEditCart(id);
}

document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('shown.bs.modal', () => {
        const cabang = modal.querySelector('.cabangEdit');
        const kayuSelect = modal.querySelector('.kayuSelect');

        if (!cabang || !kayuSelect) return;
        fetchKayu(cabang.value, kayuSelect);
    });
});


// document.querySelectorAll('.addToCart').forEach(btn => {
//     btn.addEventListener('click', () => {
//         const id = btn.dataset.id;
//         const select = document.querySelector(`.kayuSelect[data-id="${id}"]`);
//         const qtyInput = document.querySelector(`.qtyInput[data-id="${id}"]`);

//         if (!select.value || !qtyInput.value) {
//             alert('Pilih kayu dan isi jumlah');
//             return;
//         }

//         const opt = select.options[select.selectedIndex];

//         editCarts[id].push({
//             nama: opt.dataset.nama,
//             qty: parseInt(qtyInput.value),
//             subtotal: parseInt(opt.dataset.harga) * parseInt(qtyInput.value)
//         });

//         select.value = '';
//         qtyInput.value = '';

//         renderEditCart(id);
//     });
// });

document.querySelectorAll('.addToCart').forEach(btn => {
    btn.addEventListener('click', () => {

        const mode = btn.dataset.mode;

        // =======================
        // MODE TAMBAH TRANSAKSI
        // =======================
        if (mode === 'tambah') {
            const select = document.getElementById('kayuSelect');
            const qtyInput = document.getElementById('qtyInput');
            const qty = parseInt(qtyInput.value);

            if (!select.value || !qty || qty < 1) {
                alert('Pilih kayu dan isi jumlah');
                return;
            }

            const opt = select.options[select.selectedIndex];
            const subtotal = qty * parseInt(opt.dataset.harga);

            cart.push({
                nama: opt.dataset.nama,
                qty,
                subtotal
            });

            select.value = '';
            qtyInput.value = '';

            renderCartTambah();
        }

        // =======================
        // MODE EDIT TRANSAKSI
        // =======================
        if (mode === 'edit') {
            const id = btn.dataset.id;
            const modal = btn.closest('.modal-content');

            const select = modal.querySelector('.kayuSelect');
            const qtyInput = modal.querySelector('.qtyInput');

            if (!select.value || !qtyInput.value) {
                alert('Pilih kayu dan isi jumlah');
                return;
            }

            const opt = select.options[select.selectedIndex];

            editCarts[id].push({
                nama: opt.dataset.nama,
                qty: parseInt(qtyInput.value),
                subtotal: parseInt(opt.dataset.harga) * parseInt(qtyInput.value)
            });

            select.value = '';
            qtyInput.value = '';

            renderEditCart(id);
        }
    });
});



// document.querySelectorAll('#addToCart').addEventListener('click', () => {
//     const select = document.getElementById('kayuSelect');
//     const qty = parseInt(document.getElementById('qtyInput').value);

//     if (!select.value || !qty || qty < 1) {
//         alert('Pilih kayu dan isi jumlah');
//         return;
//     }

//     const option = select.options[select.selectedIndex];
//     const nama = option.dataset.nama;
//     const harga = parseInt(option.dataset.harga);
//     const subtotal = qty * harga;

//     cart.push({ nama, qty, subtotal });

//     select.value = '';
//     document.getElementById('qtyInput').value = '';

//     renderCart();
// });

function renderCart() {
    const list = document.getElementById('cartList');
    list.innerHTML = '';

    let total = 0;
    let info = '';
    let no = 1;

    cart.forEach((item, index) => {
        total += item.subtotal;

        info += `${no}. ${item.nama} (${item.qty} - Rp. ${item.subtotal.toLocaleString('id-ID')})\n`;
        no++;

        list.innerHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>${item.nama} (${item.qty})</span>
                    <div>
                        <strong>${rupiah(item.subtotal)}</strong>
                        <button  class="btn btn-sm btn-danger ms-2"
                            onclick="removeItem(${index})">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </li>
            `;
    });

    if (cart.length === 0) {
        list.innerHTML = `
                <li class="list-group-item text-muted text-center">
                    Belum ada item
                </li>
            `;
    }

    document.getElementById('informasi').value = info;
    document.getElementById('total').value = total;
    document.getElementById('totalText').innerText = rupiah(total);
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}
