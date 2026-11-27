# Pakai: python scripts/excel_to_json.py ASRI.xlsx  -> resources/data/asri.json
# Ringkasan per divisi dihitung dari sheet Master Data Modul.
# Revisi: modul no.673 (Sekper (Umum)) diganti 97 modul Keuangan (no.673-769, scripts/keuangan.txt).
import sys, json, pandas as pd
f = sys.argv[1] if len(sys.argv) > 1 else 'ASRI.xlsx'
m = pd.read_excel(f, sheet_name=1).iloc[:, :6]; m.columns = ['no', 'div', 'menu', 'nama', 'status', 'ket']
if 'Keuangan' not in set(m['div']):
    m = m[m['no'] != 673]
    rows = []
    for ln in open(__file__.replace('excel_to_json.py', 'keuangan.txt'), encoding='utf-8'):
        if ln.startswith('#') or '|' not in ln: continue
        menu, items = ln.strip().split('|'); ti = menu.startswith('**'); menu = menu.strip('*')
        rows += [dict(div='Keuangan', menu=menu, nama=i.strip(), status='Digunakan', ket='Menu File digunakan oleh TI' if ti else '') for i in items.split(';')]
    m = pd.concat([m, pd.DataFrame(rows)], ignore_index=True)
def nm(v):
    v = '' if pd.isna(v) else str(v).strip()
    return v or '(tanpa nama)'
def stat(g):
    t = len(g); u = int((g.status == 'Digunakan').sum()); un = int((g.status == 'Tidak Digunakan').sum()); up = int((g.status == 'Butuh Update').sum())
    return dict(used=u, unused=un, update=up, total=t, pct=round(u / t * 100, 1))
div, mods, flat = [], {}, []
for d, g in m.groupby('div', sort=False):
    div.append(dict(name=d, **stat(g))); menus = []
    for mn, gg in g.groupby('menu', sort=False):
        items = [[nm(x.nama), str(x.status).strip(), '' if pd.isna(x.ket) or x.ket == '' else str(x.ket).strip()] for x in gg.itertuples()]
        menus.append(dict(menu=str(mn).strip(), items=items)); flat += [(d, str(mn).strip(), i) for i in items]
    mods[d] = menus
total = dict(name='TOTAL KESELURUHAN', **stat(m))
ins = dict(
  low=[d['name'] for d in sorted(div, key=lambda x: x['pct']) if d['pct'] < 50],
  update=[dict(div=a, menu=b, nama=i[0], ket=i[2]) for a, b, i in flat if i[1] == 'Butuh Update'],
  sipas=sum('sipas' in i[2].lower() for _, _, i in flat),
  ti=sum('oleh ti' in i[2].lower() for _, _, i in flat),
  wms=sum('wms' in i[2].lower() for _, _, i in flat))
json.dump(dict(total=total, divisi=div, modules=mods, insight=ins), open('resources/data/asri.json', 'w', encoding='utf-8'), ensure_ascii=False)
print(total); print([(d['name'], d['total'], d['pct']) for d in div if d['name'] in ('Keuangan', 'Sekper (Umum)')]); print(ins['low'], ins['sipas'], ins['ti'])
