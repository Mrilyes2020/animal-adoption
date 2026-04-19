<!DOCTYPE html>
<html lang="en" dir="ltr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawHaven — Premium Animal Adoption Center</title>
    <meta name="description" content="PawHaven Dashboard for managing animal registrations, health records, and adoptions.">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- Chart.js for Stats -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <!-- Header -->
    <header>
        <div class="logo">🐾 Paw<span>Haven</span></div>
        <nav>
            <button class="active" onclick="showPage('dashboard', this)">📊 Dashboard</button>
            <button onclick="showPage('list', this)">🏠 Available Animals</button>
            <button onclick="showPage('fame', this)">🌟 Hall of Fame</button>
            <button class="admin-only hidden" onclick="showPage('add', this)">➕ Register Animal</button>
            <button class="theme-toggle" onclick="toggleTheme()">🌗</button>
            <button class="btn-login-nav guest-only" onclick="showLogin()">🔐 Login (Admin)</button>
            <button class="btn-logout admin-only hidden" onclick="logout()">🚪 Logout</button>
        </nav>
    </header>

    <!-- Main Interface -->
    <div class="hero">
        <div class="hero-text">
            <span class="hero-badge">Premium Admin Dashboard</span>
            <h1 class="hero-title">Every animal deserves<br><em>a loving home</em></h1>
            <p class="hero-sub">Manage the adoption center with advanced analytics, seamless management, and interactive UI.</p>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        <div class="pages">

            <!-- Dashboard Page -->
            <div id="page-dashboard" class="visible">
                <div class="section-header">
                    <h2>Analytics Overview</h2>
                    <p>Real-time statistics of the adoption center.</p>
                </div>
                <div class="stats-grid" id="stats-grid">
                    <!-- Stats will be populated here -->
                </div>
                <div class="charts-grid">
                    <div class="chart-container">
                        <canvas id="speciesChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <canvas id="healthChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Add Animal Page -->
            <div id="page-add">
                <div class="form-section">
                    <div class="section-header">
                        <h2>Register New Animal</h2>
                        <p>Fill out the following information and upload a photo to add the animal.</p>
                    </div>
                    <div class="form-card">
                        <form id="add-form" onsubmit="event.preventDefault(); addAnimal();">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Animal Name</label>
                                    <input type="text" id="f-name" required placeholder="— Enter Name —" autocomplete="off">

                                    <label>Species</label>
                                    <select id="f-species" required>
                                        <option value="">— Select Species —</option>
                                        <option value="Dog">🐶 Dog</option>
                                        <option value="Cat">🐱 Cat</option>
                                        <option value="Bird">🐦 Bird</option>
                                        <option value="Rabbit">🐰 Rabbit</option>
                                        <option value="Fish">🐟 Fish</option>
                                        <option value="Other">🐾 Other</option>
                                    </select>

                                    <label>Color</label>
                                    <input type="text" id="f-color" list="color-options" required placeholder="— Enter Color —" autocomplete="off">
                                    <datalist id="color-options">
                                        <option value="Black">
                                        <option value="White">
                                        <option value="Brown">
                                        <option value="Golden">
                                        <option value="Orange">
                                        <option value="Gray">
                                        <option value="Mixed">
                                    </datalist>

                                    <label>Age (in months)</label>
                                    <input type="number" id="f-age" required placeholder="0" min="0" max="240">
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <div class="radio-group">
                                        <label class="radio-option">
                                            <input type="radio" name="gender" value="Male" required>
                                            <span class="icon">♂</span> Male
                                        </label>
                                        <label class="radio-option">
                                            <input type="radio" name="gender" value="Female">
                                            <span class="icon">♀</span> Female
                                        </label>
                                    </div>

                                    <label>Health Status</label>
                                    <select id="f-health" required>
                                        <option value="Healthy">✅ Healthy</option>
                                        <option value="Under Treatment">🩺 Under Treatment</option>
                                    </select>

                                    <label>Animal Photo (Optional)</label>
                                    <input type="file" id="f-image" accept="image/*">
                                </div>

                                <button type="submit" class="submit-btn admin-only">
                                    <span class="paw">🐾</span> Register Animal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Animals List Page -->
            <div id="page-list">
                <div class="section-header">
                    <h2>Available Animals</h2>
                    <p>Animals currently waiting for a loving home.</p>
                </div>
                <div class="list-controls">
                    <div class="search-wrap">
                        <input type="text" id="search-input" placeholder="Search by name, species, or color…" oninput="renderList('Available')">
                    </div>
                    <select class="filter-select" id="filter-species" onchange="renderList('Available')">
                        <option value="">All Species</option>
                        <option value="Dog">🐶 Dog</option>
                        <option value="Cat">🐱 Cat</option>
                        <option value="Bird">🐦 Bird</option>
                        <option value="Rabbit">🐰 Rabbit</option>
                        <option value="Fish">🐟 Fish</option>
                        <option value="Other">🐾 Other</option>
                    </select>
                    <select class="filter-select" id="filter-health" onchange="renderList('Available')">
                        <option value="">All Statuses</option>
                        <option value="Healthy">✅ Healthy</option>
                        <option value="Under Treatment">🩺 Under Treatment</option>
                    </select>
                    <button class="btn-export admin-only hidden" onclick="exportData()">📥 Export to CSV</button>
                </div>
                <div class="animals-grid" id="animals-grid-Available"></div>
            </div>

            <!-- Hall of Fame Page -->
            <div id="page-fame">
                <div class="section-header">
                    <h2>🌟 Hall of Fame</h2>
                    <p>Celebrate the animals that successfully found their forever homes!</p>
                </div>
                <div class="animals-grid" id="animals-grid-Adopted"></div>
            </div>

        </div>
    </main>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <span class="toast-icon" id="toast-icon">🐾</span>
        <span class="toast-msg" id="toast-msg">Notification</span>
    </div>

    <!-- Login Modal -->
    <div class="modal-overlay" id="login-modal">
        <div class="modal">
            <h3>🔐 Admin Login</h3>
            <p style="margin-bottom: 20px; color: var(--text-dim); font-size: 0.9rem;">Default Credentials: admin / admin123</p>
            <form id="login-form" onsubmit="event.preventDefault(); submitLogin();">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" id="l-user" required autocomplete="username">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="l-pass" required autocomplete="current-password">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('login-modal')">Cancel</button>
                    <button type="submit" class="btn-save">Login</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal-overlay" id="edit-modal">
        <div class="modal">
            <h3>✏️ Edit Animal</h3>
            <form id="edit-form" onsubmit="event.preventDefault(); saveEdit();">
                <input type="hidden" id="e-id">
                <div class="form-group">
                    <label>Animal Name</label>
                    <input type="text" id="e-name" required>
                </div>
                <div class="form-group">
                    <label>Species</label>
                    <select id="e-species" required>
                        <option value="Dog">🐶 Dog</option>
                        <option value="Cat">🐱 Cat</option>
                        <option value="Bird">🐦 Bird</option>
                        <option value="Rabbit">🐰 Rabbit</option>
                        <option value="Fish">🐟 Fish</option>
                        <option value="Other">🐾 Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Color</label>
                    <input type="text" id="e-color" list="e-color-options" required autocomplete="off">
                    <datalist id="e-color-options">
                        <option value="Black">
                        <option value="White">
                        <option value="Brown">
                        <option value="Golden">
                        <option value="Orange">
                        <option value="Gray">
                        <option value="Mixed">
                    </datalist>
                </div>
                <div class="form-group">
                    <label>Age (in months)</label>
                    <input type="number" id="e-age" required min="0" max="240">
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select id="e-gender" required>
                        <option value="Male">♂ Male</option>
                        <option value="Female">♀ Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Health Status</label>
                    <select id="e-health" required>
                        <option value="Healthy">✅ Healthy</option>
                        <option value="Under Treatment">🩺 Under Treatment</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Update Photo (Optional)</label>
                    <input type="file" id="e-image" accept="image/*">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal('edit-modal')">Cancel</button>
                    <button type="submit" class="btn-save admin-only">💾 Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <strong>PawHaven Premium</strong> — Animal Adoption Center Dashboard &nbsp;|&nbsp; M'sila University · Web App Development
        2025/2026 &nbsp;|&nbsp; Dr. KIHAL
    </footer>

    <script>
        const API = 'backend.php';
        let isAdmin = false;
        let speciesChartInst = null;
        let healthChartInst = null;

        // Theme Management
        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            if(speciesChartInst) updateChartColors();
        }

        function updateChartColors() {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const color = isDark ? '#e4e6ef' : '#0f172a';
            Chart.defaults.color = color;
            if(speciesChartInst) speciesChartInst.update();
            if(healthChartInst) healthChartInst.update();
        }

        // Init
        document.addEventListener('DOMContentLoaded', async () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            Chart.defaults.font.family = "'DM Sans', sans-serif";
            updateChartColors();

            await checkAuth();
            showPage('dashboard', document.querySelector('nav button'));
        });

        // Navigation
        function showPage(page, btn) {
            document.querySelectorAll('.pages > div').forEach(d => d.classList.remove('visible'));
            document.getElementById('page-' + page).classList.add('visible');
            document.querySelectorAll('nav button').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');
            
            if (page === 'list') renderList('Available');
            if (page === 'fame') renderList('Adopted');
            if (page === 'dashboard') loadStats();
        }

        // Auth
        async function checkAuth() {
            try {
                const res = await fetch(`${API}?action=check_auth`);
                const data = await res.json();
                isAdmin = data.data.is_admin;
                toggleAdminUI();
            } catch(e){}
        }

        function toggleAdminUI() {
            document.querySelectorAll('.admin-only').forEach(el => {
                isAdmin ? el.classList.remove('hidden') : el.classList.add('hidden');
            });
            document.querySelectorAll('.guest-only').forEach(el => {
                !isAdmin ? el.classList.remove('hidden') : el.classList.add('hidden');
            });
            
            // Re-render lists to show/hide admin buttons
            if(document.getElementById('page-list').classList.contains('visible')) renderList('Available');
            if(document.getElementById('page-fame').classList.contains('visible')) renderList('Adopted');
        }

        function showLogin() {
            document.getElementById('login-modal').classList.add('show');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('show');
        }

        async function submitLogin() {
            const u = document.getElementById('l-user').value;
            const p = document.getElementById('l-pass').value;
            const res = await fetch(`${API}?action=login`, {
                method: 'POST', headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({username: u, password: p})
            });
            const data = await res.json();
            if(data.success) {
                showNote('🔐', 'Logged in as Admin');
                closeModal('login-modal');
                isAdmin = true;
                toggleAdminUI();
                document.getElementById('l-pass').value = '';
            } else {
                showNote('❌', data.message);
            }
        }

        async function logout() {
            await fetch(`${API}?action=logout`);
            isAdmin = false;
            toggleAdminUI();
            showNote('👋', 'Logged out successfully');
            showPage('dashboard', document.querySelector('nav button'));
        }

        // Dashboard Stats
        async function loadStats() {
            try {
                const res = await fetch(`${API}?action=stats`);
                const data = await res.json();
                if(data.success) {
                    const s = data.data;
                    const available = s.total - s.adopted;
                    document.getElementById('stats-grid').innerHTML = `
                        <div class="stat-card"><h4>Total Registered</h4><div class="value">${s.total}</div></div>
                        <div class="stat-card"><h4>Available</h4><div class="value" style="color:var(--accent)">${available}</div></div>
                        <div class="stat-card"><h4>Happily Adopted 🏡</h4><div class="value" style="color:var(--green)">${s.adopted}</div></div>
                        <div class="stat-card"><h4>Under Treatment</h4><div class="value" style="color:var(--red)">${s.treatment}</div></div>
                    `;

                    // Render Charts
                    const labels = s.species.map(x => x.species);
                    const counts = s.species.map(x => x.count);
                    
                    if(speciesChartInst) speciesChartInst.destroy();
                    const ctxS = document.getElementById('speciesChart').getContext('2d');
                    speciesChartInst = new Chart(ctxS, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{ data: counts, backgroundColor: ['#6366f1','#10b981','#f59e0b','#ef4444','#3b82f6','#8b5cf6'], borderWidth: 0 }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { title: {display: true, text: 'Animals by Species'} }, cutout: '70%' }
                    });

                    if(healthChartInst) healthChartInst.destroy();
                    const ctxH = document.getElementById('healthChart').getContext('2d');
                    healthChartInst = new Chart(ctxH, {
                        type: 'pie',
                        data: {
                            labels: ['Healthy', 'Under Treatment'],
                            datasets: [{ data: [s.total - s.treatment, s.treatment], backgroundColor: ['#10b981', '#ef4444'], borderWidth: 0 }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { title: {display: true, text: 'Health Status Ratio'} } }
                    });
                }
            } catch(e) {}
        }

        // CRUD Operations
        async function addAnimal() {
            if(!isAdmin) return showNote('🚫', 'Admin only');
            
            const formData = new FormData();
            formData.append('name', document.getElementById('f-name').value);
            formData.append('species', document.getElementById('f-species').value);
            formData.append('color', document.getElementById('f-color').value);
            formData.append('age', document.getElementById('f-age').value);
            formData.append('gender', document.querySelector('input[name="gender"]:checked').value);
            formData.append('health', document.getElementById('f-health').value);
            
            const file = document.getElementById('f-image').files[0];
            if(file) formData.append('image', file);

            try {
                const res = await fetch(`${API}?action=add`, { method: 'POST', body: formData });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('add-form').reset();
                    showNote('🐾', data.message);
                } else showNote('❌', data.message);
            } catch (err) { console.error(err); showNote('❌', 'Server error'); }
        }

        async function renderList(status) {
            const gridId = `animals-grid-${status}`;
            const grid = document.getElementById(gridId);
            if(!grid) return;

            let url = `${API}?action=list&status=${status}`;
            if(status === 'Available') {
                const sp = document.getElementById('filter-species').value;
                const hl = document.getElementById('filter-health').value;
                const se = document.getElementById('search-input').value;
                if(sp) url += `&species=${sp}`;
                if(hl) url += `&health=${hl}`;
                if(se) url += `&search=${se}`;
            }

            try {
                const res = await fetch(url);
                const data = await res.json();

                if (!data.success || !data.data.length) {
                    grid.innerHTML = `<div class="empty-state"><div class="icon">${status==='Adopted'?'🌟':'🐾'}</div><p>No animals found here.</p></div>`;
                    return;
                }

                const sEmoji = {Dog:'🐶', Cat:'🐱', Bird:'🐦', Rabbit:'🐰', Fish:'🐟', Other:'🐾'};

                grid.innerHTML = data.data.map(a => {
                    const hClass = a.health_status === 'Healthy' ? 'healthy' : 'treatment';
                    const gClass = a.gender === 'Male' ? 'male' : 'female';
                    
                    const imageHtml = a.image_path 
                        ? `<img src="${a.image_path}" class="animal-image" alt="${a.name}">`
                        : `<div class="animal-image-placeholder">${sEmoji[a.species]||'🐾'}</div>`;

                    let actionsHtml = '';
                    if (isAdmin) {
                        actionsHtml = `<div class="card-actions">`;
                        if (status === 'Available') {
                            actionsHtml += `
                                <button class="btn-edit" onclick='openEdit(${JSON.stringify(a)})'>✏️ Edit</button>
                                <button class="btn-adopt" onclick="adoptAnimal(${a.id})">🏡 Adopted</button>
                            `;
                        }
                        actionsHtml += `<button class="btn-delete" onclick="deleteAnimal(${a.id}, '${status}')">🗑️ Delete</button></div>`;
                    }

                    return `
                    <div class="animal-card">
                        ${imageHtml}
                        <div class="card-body">
                            <div class="card-header">
                                <h3>${a.name}</h3>
                                <span class="card-species">${sEmoji[a.species]||'🐾'} ${a.species}</span>
                            </div>
                            <div class="card-details">
                                <span class="card-tag">${a.color}</span>
                                <span class="card-tag">${a.age} months</span>
                                <span class="card-tag ${gClass}">${a.gender==='Male'?'♂':'♀'} ${a.gender}</span>
                                <span class="card-tag ${hClass}">${a.health_status}</span>
                                ${status === 'Adopted' ? `<span class="card-tag adopted">🌟 Adopted</span>` : ''}
                            </div>
                            ${actionsHtml}
                        </div>
                    </div>`;
                }).join('');
            } catch (err) {
                grid.innerHTML = `<div class="empty-state"><p>Server error.</p></div>`;
            }
        }

        async function adoptAnimal(id) {
            if(!isAdmin) return;
            if(!confirm('Mark this animal as adopted? It will move to the Hall of Fame! 🌟')) return;
            const res = await fetch(`${API}?action=adopt`, {
                method: 'POST', headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id})
            });
            const data = await res.json();
            if(data.success) { showNote('🎉', data.message); renderList('Available'); }
            else showNote('❌', data.message);
        }

        async function deleteAnimal(id, status) {
            if(!isAdmin) return;
            if(!confirm('Permanently delete this animal? This cannot be undone.')) return;
            const res = await fetch(`${API}?action=delete`, {
                method: 'POST', headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id})
            });
            const data = await res.json();
            if(data.success) { showNote('🗑️', data.message); renderList(status); }
            else showNote('❌', data.message);
        }

        function openEdit(a) {
            document.getElementById('e-id').value = a.id;
            document.getElementById('e-name').value = a.name;
            document.getElementById('e-species').value = a.species;
            document.getElementById('e-color').value = a.color;
            document.getElementById('e-age').value = a.age;
            document.getElementById('e-gender').value = a.gender;
            document.getElementById('e-health').value = a.health_status;
            document.getElementById('edit-modal').classList.add('show');
        }

        async function saveEdit() {
            if(!isAdmin) return;
            const formData = new FormData();
            formData.append('id', document.getElementById('e-id').value);
            formData.append('name', document.getElementById('e-name').value);
            formData.append('species', document.getElementById('e-species').value);
            formData.append('color', document.getElementById('e-color').value);
            formData.append('age', document.getElementById('e-age').value);
            formData.append('gender', document.getElementById('e-gender').value);
            formData.append('health', document.getElementById('e-health').value);
            
            const file = document.getElementById('e-image').files[0];
            if(file) formData.append('image', file);

            const res = await fetch(`${API}?action=update`, { method: 'POST', body: formData });
            const data = await res.json();
            if(data.success) {
                closeModal('edit-modal');
                showNote('✅', data.message);
                renderList('Available');
            } else showNote('❌', data.message);
        }

        function exportData() {
            window.location.href = `${API}?action=export`;
        }

        // Toasts
        let toastTimer;
        function showNote(icon, msg) {
            clearTimeout(toastTimer);
            document.getElementById('toast-icon').textContent = icon;
            document.getElementById('toast-msg').textContent  = msg;
            document.getElementById('toast').classList.add('show');
            toastTimer = setTimeout(() => document.getElementById('toast').classList.remove('show'), 3200);
        }
    </script>
</body>
</html>