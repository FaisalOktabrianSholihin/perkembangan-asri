(function () {
  const D = window.ASRI, $ = (s) => document.querySelector(s);
  const slides = [...document.querySelectorAll('.slide')], dots = $('#dots');
  const cls = { 'Digunakan': 'ok', 'Tidak Digunakan': 'no', 'Butuh Update': 'up' };
  const esc = s => String(s).replace(/[&<>"]/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
  const pf = n => n.toLocaleString('id-ID', { maximumFractionDigits: 1 });

  slides.forEach(s => {
    const b = document.createElement('button');
    b.title = s.dataset.title; b.onclick = () => s.scrollIntoView({ behavior: 'smooth' });
    dots.appendChild(b);
  });
  const io = new IntersectionObserver(es => es.forEach(e => {
    if (e.isIntersecting) [...dots.children].forEach((d, i) => d.classList.toggle('on', slides[i] === e.target));
  }), { threshold: .55 });
  slides.forEach(s => io.observe(s));

  const cur = () => slides.findIndex(s => Math.abs(s.getBoundingClientRect().top) < innerHeight / 2);
  function open(name) {
    const d = D.divisi.find(x => x.name === name), menus = D.modules[name] || [];
    $('#mbody').innerHTML = `<p class="eyebrow">Detail Divisi</p><h2>${esc(name)}</h2>
      <p class="sub">${d.total} modul · <b class="g">${d.used} digunakan</b> · <b class="r">${d.unused} tidak digunakan</b>${d.update ? ` · <b class="a">${d.update} butuh update</b>` : ''} · ${pf(d.pct)}%</p>
      <div class="menus">${menus.map(m => {
        const u = m.items.filter(i => i[1] === 'Digunakan').length, p = u / m.items.length * 100;
        return `<div class="menu"><div class="mh"><b>${esc(m.menu)}</b><span>${u}/${m.items.length} digunakan</span></div>
          <div class="mini"><i style="width:${p}%"></i></div>
          <div class="chips">${m.items.map(i => `<span class="chip ${cls[i[1]]}" title="${esc(i[1])}">${esc(i[0])}${i[2] ? `<small>${esc(i[2])}</small>` : ''}</span>`).join('')}</div></div>`;
      }).join('')}</div>`;
    $('#modal').hidden = false; $('#mbody').scrollTop = 0; document.body.style.overflow = 'hidden';
  }
  const close = () => { $('#modal').hidden = true; document.body.style.overflow = ''; };
  document.querySelectorAll('[data-div]').forEach(el => el.onclick = () => open(el.dataset.div));
  $('#close').onclick = close; $('#modal').onclick = e => { if (e.target.id === 'modal') close(); };

  addEventListener('keydown', e => {
    if (e.key === 'Escape') return close();
    if (!$('#modal').hidden) return;
    const i = cur();
    if (['ArrowRight', 'ArrowDown', 'PageDown'].includes(e.key) && slides[i + 1]) { e.preventDefault(); slides[i + 1].scrollIntoView({ behavior: 'smooth' }); }
    if (['ArrowLeft', 'ArrowUp', 'PageUp'].includes(e.key) && slides[i - 1]) { e.preventDefault(); slides[i - 1].scrollIntoView({ behavior: 'smooth' }); }
  });
})();
