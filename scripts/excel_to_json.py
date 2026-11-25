# Pakai: python scripts/excel_to_json.py ASRI.xlsx  -> resources/data/asri.json
import sys, json, pandas as pd
f = sys.argv[1] if len(sys.argv) > 1 else 'ASRI.xlsx'
s = pd.read_excel(f, sheet_name=0); m = pd.read_excel(f, sheet_name=1).iloc[:, :6]
m.columns = ['no','div','menu','nama','status','ket']
div, total = [], None
for _, r in s.iterrows():
    row = dict(name=r['Divisi'], used=int(r['Digunakan']), unused=int(r['Tidak Digunakan']),
               update=int(r['Butuh Update']), total=int(r['Total Modul']), pct=round(float(r['% Digunakan'])*100, 1))
    if str(r['Divisi']).startswith('TOTAL'): total = row
    else: div.append(row)
def nm(v):
    v = '' if pd.isna(v) else str(v).strip()
    return v or '(tanpa nama)'
mods, flat = {}, []
for d, g in m.groupby('div', sort=False):
    menus = []
    for mn, gg in g.groupby('menu', sort=False):
        items = [[nm(x.nama), str(x.status).strip(), '' if pd.isna(x.ket) else str(x.ket).strip()] for x in gg.itertuples()]
        menus.append(dict(menu=str(mn).strip(), items=items))
        flat += [(d, str(mn).strip(), i) for i in items]
    mods[d] = menus
ins = dict(
  low=[d['name'] for d in sorted(div, key=lambda x: x['pct']) if d['pct'] < 50],
  update=[dict(div=a, menu=b, nama=i[0], ket=i[2]) for a, b, i in flat if i[1] == 'Butuh Update'],
  sipas=sum('sipas' in i[2].lower() for _, _, i in flat),
  ti=sum('oleh ti' in i[2].lower() for _, _, i in flat),
  wms=sum('wms' in i[2].lower() for _, _, i in flat))
json.dump(dict(total=total, divisi=div, modules=mods, insight=ins), open('resources/data/asri.json', 'w'), ensure_ascii=False)
print(ins)
