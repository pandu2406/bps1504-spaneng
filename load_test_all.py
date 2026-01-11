import requests
import time
import concurrent.futures
import statistics
import sys
import random
import threading

BASE_URL = "http://localhost:8001"
REQUESTS_PER_ENDPOINT = 50 # Modified as per request
CONCURRENT_REQUESTS = 50    # Modified as per request
TIMEOUT_SEC = 30

# Expanded Endpoint List
ENDPOINTS = [
    # Auth & Base
    "/", "/welcome", "/auth", "/auth/registration", "/auth/blocked",
    "/admin", "/admin/role", "/admin/roleaccess/1", "/admin/roleaccess/2",
    "/user", "/user/edit", "/user/changepassword",
    "/menu", "/menu/submenu", "/menu/edit/1", "/menu/submenuedit/1",
    "/notifikasi",
    
    # Master Data
    "/master", 
    "/master/mitra", "/master/mitra?page=1", "/master/mitra?page=2", 
    "/master/pegawai", "/master/pegawai?page=1",
    "/master/posisi", "/master/posisi?page=1",
    "/master/golongan", 
    "/master/sistempembayaran",
    "/master/wilayah",
    
    # Kegiatan
    "/kegiatan", "/kegiatan/index", 
    "/kegiatan/tambah", 
    "/kegiatan/editsurvei/1", "/kegiatan/editsurvei/2", "/kegiatan/editsurvei/3",
    "/kegiatan/detail/1", "/kegiatan/detail/2",
    
    # Penilaian
    "/penilaian", "/penilaian/index",
    "/penilaian/laporan", 
    "/penilaian/detail/1", 
    "/penilaian/input/1",
    
    # Ranking & Criteria
    "/ranking", 
    "/ranking/kriteria", "/ranking/editkriteria/1",
    "/ranking/subkriteria", 
    "/ranking/pilih_kegiatan", 
    "/ranking/hasil/1",
    
    # Rekap
    "/rekap", "/rekap/index",
    "/rekap/export_excel/0/0", "/rekap/export_excel/1/2025",
    "/rekap/export_nilai_excel/0/0", "/rekap/export_nilai_excel/1/2025",
    "/rekap/details_mitra/1/1/2025", "/rekap/details_mitra/2/1/2025",
    
    # Persuratan
    "/persuratan", 
    "/persuratan/surat_tugas", "/persuratan/surat_tugas/cetak/1",
    "/persuratan/lpd", 
    "/persuratan/lpd_pegawai", "/persuratan/lpd_pegawai/cetak/1",
    "/persuratan/lpd_mitra", "/persuratan/lpd_mitra/cetak/1",
    "/persuratan/nomor_surat",
    
    # API / AJAX endpoints (simulated)
    "/master/get_mitra_by_id/1",
    "/kegiatan/get_rincian/1",
    "/penilaian/get_nilai/1",
    
    # Variations
    "/master/mitra?search=a",
    "/master/pegawai?search=b",
    "/kegiatan?search=sensus",
    "/penilaian?bulan=1&tahun=2025",
    "/rekap?bulan=1&tahun=2025",
    "/persuratan?jenis=tugas",
    
    # Errors (to test handling)
    "/admin/invalid_page",
    "/kegiatan/detail/99999",
]

# Ensure we have enough (duplicate with params if needed to hit 100 distinct URLs)
while len(ENDPOINTS) < 100: # Modified to 100
    ENDPOINTS.append(f"/master/mitra?random={random.randint(1,10000)}")

# Trim to 100 if over
ENDPOINTS = ENDPOINTS[:100] # Modified to 100

session = requests.Session()
adapter = requests.adapters.HTTPAdapter(pool_connections=CONCURRENT_REQUESTS, pool_maxsize=CONCURRENT_REQUESTS)
session.mount('http://', adapter)

print(f"Starting HIGH CONCURRENCY Load Test on {BASE_URL}")
print(f"Endpoints: {len(ENDPOINTS)}")
print(f"Requests per Endpoint: {REQUESTS_PER_ENDPOINT}")
print(f"Concurrency: {CONCURRENT_REQUESTS}")
print(f"Total Requests: {len(ENDPOINTS) * REQUESTS_PER_ENDPOINT}")
print("---------------------------------------------------")
sys.stdout.flush()

results = []
completed_requests = 0
lock = threading.Lock()
start_global = time.time()
total_requests = len(ENDPOINTS) * REQUESTS_PER_ENDPOINT

def do_request(url):
    global completed_requests
    try:
        start_req = time.time()
        resp = session.get(url, allow_redirects=True, timeout=TIMEOUT_SEC)
        duration = time.time() - start_req
        status = resp.status_code
    except Exception:
        duration = 0
        status = "Error"
    
    with lock:
        completed_requests += 1
        curr = completed_requests
        
    return duration, status

def test_endpoint(endpoint):
    url = f"{BASE_URL}{endpoint}"
    times = []
    status_counts = {}
    
    for _ in range(REQUESTS_PER_ENDPOINT):
        d, s = do_request(url)
        if d > 0: times.append(d)
        status_counts[s] = status_counts.get(s, 0) + 1
            
    avg_time = statistics.mean(times) if times else 0
    max_time = max(times) if times else 0
    dom_status = max(status_counts, key=status_counts.get) if status_counts else "None"
    
    return {
        "endpoint": endpoint,
        "avg_time": avg_time,
        "max_time": max_time,
        "status": dom_status,
        "success_rate": (status_counts.get(200, 0) / REQUESTS_PER_ENDPOINT) * 100
    }

# Monitor Thread
def monitor_progress():
    while completed_requests < total_requests:
        time.sleep(10)
        elapsed = time.time() - start_global
        if elapsed > 0:
            rate = completed_requests / elapsed
            percent = (completed_requests / total_requests) * 100
            print(f"[PROGRESS] {percent:.1f}% ({completed_requests}/{total_requests}) | Rate: {rate:.1f} req/s | Elapsed: {elapsed:.0f}s")
            sys.stdout.flush()

monitor_thread = threading.Thread(target=monitor_progress, daemon=True)
monitor_thread.start()

with concurrent.futures.ThreadPoolExecutor(max_workers=CONCURRENT_REQUESTS) as executor:
    
    future_to_url = {executor.submit(test_endpoint, ep): ep for ep in ENDPOINTS}
    
    for future in concurrent.futures.as_completed(future_to_url):
        res = future.result()
        results.append(res)

total_duration = time.time() - start_global

print("\n\n--- Final Stress Test Summary ---")
print(f"{'Endpoint':<40} | {'Avg Time':<10} | {'Max Time':<10} | {'Status'}")
print("-" * 80)

slow_pages = []
for r in sorted(results, key=lambda x: x['avg_time'], reverse=True):
    print(f"{r['endpoint']:<40} | {r['avg_time']:.3f}s    | {r['max_time']:.3f}s    | {r['status']}")
    if r['avg_time'] > 1.0:
        slow_pages.append(r['endpoint'])

print("-" * 80)
print(f"Total Duration: {total_duration:.2f}s")
print(f"Global Rate: {total_requests/total_duration:.1f} req/s")
if slow_pages:
    print(f"SLOWNESS DETECTED (>1s): {len(slow_pages)} endpoints")
else:
    print("All endpoints average < 1.0s under heavy load!")