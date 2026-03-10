<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management</title>
    <style>
        :root { color-scheme: light; }
        body { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji","Segoe UI Emoji"; margin: 0; background: #f6f7fb; color: #111827; }
        .container { max-width: 1100px; margin: 0 auto; padding: 24px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 1px 2px rgba(0,0,0,.04); }
        .card-h { padding: 16px 16px 0 16px; }
        .card-b { padding: 16px; }
        h1 { margin: 0 0 6px 0; font-size: 20px; }
        .muted { color: #6b7280; font-size: 13px; }
        .grid { display: grid; grid-template-columns: 1fr; gap: 16px; }
        @media (min-width: 900px) { .grid { grid-template-columns: 420px 1fr; align-items: start; } }
        label { display: block; font-size: 12px; color: #374151; margin-bottom: 6px; }
        input { width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 10px; outline: none; }
        input:focus { border-color: #60a5fa; box-shadow: 0 0 0 3px rgba(96,165,250,.25); }
        .row { display: grid; grid-template-columns: 1fr; gap: 12px; }
        @media (min-width: 520px) { .row.two { grid-template-columns: 1fr 1fr; } }
        button { border: 0; border-radius: 10px; padding: 10px 12px; cursor: pointer; font-weight: 600; }
        .btn { background: #2563eb; color: #fff; }
        .btn:disabled { opacity: .6; cursor: not-allowed; }
        .btn-secondary { background: #111827; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-ghost { background: transparent; border: 1px solid #d1d5db; color: #111827; }
        .toolbar { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: space-between; }
        .toolbar-left { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
        .badge { font-size: 12px; padding: 6px 10px; border-radius: 999px; background: #eef2ff; color: #3730a3; border: 1px solid #e0e7ff; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 8px; border-bottom: 1px solid #e5e7eb; font-size: 14px; vertical-align: middle; }
        th { text-align: left; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: .06em; }
        .actions { display: flex; gap: 8px; justify-content: flex-end; }
        .tiny { padding: 7px 10px; border-radius: 10px; font-size: 13px; }
        .err { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 12px; border-radius: 10px; font-size: 13px; }
        .ok { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 10px 12px; border-radius: 10px; font-size: 13px; }
        .spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.5); border-top-color: #fff; border-radius: 50%; animation: spin .8s linear infinite; vertical-align: -2px; margin-right: 8px; }
        @keyframes spin { to { transform: rotate(360deg);} }
        .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
    </style>
</head>
<body>
<div class="container">
    <div class="card" style="margin-bottom: 16px;">
        <div class="card-b">
            <h1>User Management</h1>
            <div class="muted">Simple UI calling your REST API at <span class="mono">/api/users</span>.</div>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <div class="card-h">
                <div style="display:flex; align-items:center; justify-content:space-between; gap: 10px;">
                    <div>
                        <div style="font-weight:700;">Create user</div>
                        <div class="muted">POST <span class="mono">/api/users</span></div>
                    </div>
                    <span class="badge" id="apiBaseBadge">API: /api</span>
                </div>
            </div>
            <div class="card-b">
                <div id="createMsg" style="display:none;"></div>
                <form id="createForm">
                    <div class="row">
                        <div>
                            <label for="name">Name</label>
                            <input id="name" name="name" required maxlength="255" autocomplete="name">
                        </div>
                        <div>
                            <label for="email">Email</label>
                            <input id="email" name="email" required autocomplete="email">
                        </div>
                        <div>
                            <label for="password">Password</label>
                            <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password">
                        </div>
                    </div>
                    <div style="margin-top: 12px; display:flex; gap: 10px; align-items:center;">
                        <button class="btn" id="createBtn" type="submit">Create</button>
                        <button class="btn-ghost" type="button" id="createReset">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-b">
                <div class="toolbar" style="margin-bottom: 12px;">
                    <div class="toolbar-left">
                        <div style="font-weight:700;">Users</div>
                        <span class="badge" id="countBadge">Loading…</span>
                    </div>
                    <div class="toolbar-left">
                        <input id="search" placeholder="Search name or email…" style="width: 260px;">
                        <select id="sortDir" style="padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 10px;">
                            <option value="desc">Newest first</option>
                            <option value="asc">Oldest first</option>
                        </select>
                        <select id="perPage" style="padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 10px;">
                            <option value="5">5 / page</option>
                            <option value="10">10 / page</option>
                            <option value="15" selected>15 / page</option>
                            <option value="25">25 / page</option>
                            <option value="50">50 / page</option>
                        </select>
                        <button class="btn-secondary tiny" id="refreshBtn" type="button">Refresh</button>
                    </div>
                </div>

                <div id="listMsg" style="display:none; margin-bottom: 10px;"></div>

                <div style="overflow:auto;">
                    <table>
                        <thead>
                        <tr>
                            <th style="width:70px;">ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th style="width:170px;">Created</th>
                            <th style="width:260px; text-align:right;">Actions</th>
                        </tr>
                        </thead>
                        <tbody id="tbody"></tbody>
                    </table>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; gap: 10px; margin-top: 12px;">
                    <div class="muted" id="pageInfo">—</div>
                    <div style="display:flex; gap: 8px;">
                        <button class="btn-ghost tiny" id="prevBtn" type="button">Prev</button>
                        <button class="btn-ghost tiny" id="nextBtn" type="button">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="rowTpl">
    <tr>
        <td class="mono" data-id></td>
        <td><input data-name></td>
        <td><input data-email></td>
        <td class="muted" data-created></td>
        <td>
            <div class="actions">
                <button class="btn tiny" data-save>Save</button>
                <button class="btn-danger tiny" data-del>Delete</button>
            </div>
        </td>
    </tr>
</template>

<script>
    const apiBase = '/api';
    const el = (id) => document.getElementById(id);

    const state = {
        page: 1,
        perPage: 15,
        sortDir: 'desc',
        search: '',
        loading: false,
        last: { meta: null, links: null, data: [] },
    };

    function showMsg(targetEl, type, text) {
        targetEl.className = type === 'ok' ? 'ok' : 'err';
        targetEl.textContent = text;
        targetEl.style.display = 'block';
    }

    function hideMsg(targetEl) {
        targetEl.style.display = 'none';
        targetEl.textContent = '';
    }

    async function apiFetch(path, options = {}) {
        const res = await fetch(`${apiBase}${path}`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                ...(options.headers || {}),
            },
            ...options,
        });

        const contentType = res.headers.get('content-type') || '';
        const body = contentType.includes('application/json') ? await res.json() : await res.text();

        if (!res.ok) {
            const err = new Error('Request failed');
            err.status = res.status;
            err.body = body;
            throw err;
        }

        return body;
    }

    function formatCreatedAt(createdAt) {
        if (!createdAt) return '—';
        const d = new Date(createdAt);
        if (Number.isNaN(d.getTime())) return String(createdAt);
        return d.toLocaleString();
    }

    function renderTable(users) {
        const tbody = el('tbody');
        tbody.innerHTML = '';

        const tpl = el('rowTpl');
        for (const u of users) {
            const node = tpl.content.cloneNode(true);
            node.querySelector('[data-id]').textContent = u.id;
            node.querySelector('[data-name]').value = u.name ?? '';
            node.querySelector('[data-email]').value = u.email ?? '';
            node.querySelector('[data-created]').textContent = formatCreatedAt(u.created_at);

            const saveBtn = node.querySelector('[data-save]');
            const delBtn = node.querySelector('[data-del]');
            const nameInput = node.querySelector('[data-name]');
            const emailInput = node.querySelector('[data-email]');

            saveBtn.addEventListener('click', async () => {
                hideMsg(el('listMsg'));
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner"></span>Saving';
                try {
                    const payload = {
                        name: nameInput.value,
                        email: emailInput.value,
                    };
                    await apiFetch(`/users/${u.id}`, {
                        method: 'PUT',
                        body: JSON.stringify(payload),
                    });
                    showMsg(el('listMsg'), 'ok', 'User updated.');
                    await loadUsers();
                } catch (e) {
                    showMsg(el('listMsg'), 'err', humanizeApiError(e));
                } finally {
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save';
                }
            });

            delBtn.addEventListener('click', async () => {
                if (!confirm(`Delete user #${u.id}?`)) return;
                hideMsg(el('listMsg'));
                delBtn.disabled = true;
                delBtn.innerHTML = '<span class="spinner"></span>Deleting';
                try {
                    await apiFetch(`/users/${u.id}`, { method: 'DELETE' });
                    showMsg(el('listMsg'), 'ok', 'User deleted.');
                    await loadUsers();
                } catch (e) {
                    showMsg(el('listMsg'), 'err', humanizeApiError(e));
                } finally {
                    delBtn.disabled = false;
                    delBtn.textContent = 'Delete';
                }
            });

            tbody.appendChild(node);
        }
    }

    function renderPager(meta, links) {
        if (!meta) {
            el('pageInfo').textContent = '—';
            return;
        }

        el('countBadge').textContent = `${meta.total} total`;
        el('pageInfo').textContent = `Page ${meta.current_page} of ${meta.last_page} • Showing ${meta.from ?? 0}-${meta.to ?? 0}`;

        el('prevBtn').disabled = !links?.prev;
        el('nextBtn').disabled = !links?.next;
    }

    function humanizeApiError(e) {
        const status = e?.status;
        const body = e?.body;

        if (status === 422 && body && body.errors) {
            const parts = [];
            for (const [field, msgs] of Object.entries(body.errors)) {
                parts.push(`${field}: ${Array.isArray(msgs) ? msgs.join(', ') : String(msgs)}`);
            }
            return parts.join(' | ');
        }

        if (body && body.message) return body.message;
        if (typeof body === 'string' && body.trim() !== '') return body;
        return `Request failed (${status ?? 'unknown'}).`;
    }

    async function loadUsers() {
        if (state.loading) return;
        state.loading = true;
        hideMsg(el('listMsg'));
        el('refreshBtn').disabled = true;
        el('refreshBtn').textContent = 'Loading…';

        try {
            const q = new URLSearchParams();
            q.set('page', String(state.page));
            q.set('per_page', String(state.perPage));
            q.set('sort_dir', state.sortDir);
            if (state.search.trim() !== '') q.set('search', state.search.trim());

            const res = await apiFetch(`/users?${q.toString()}`, { method: 'GET' });
            state.last = res;

            renderTable(res.data || []);
            renderPager(res.meta, res.links);
        } catch (e) {
            showMsg(el('listMsg'), 'err', humanizeApiError(e));
            el('countBadge').textContent = 'Error';
            el('pageInfo').textContent = '—';
            el('tbody').innerHTML = '';
        } finally {
            state.loading = false;
            el('refreshBtn').disabled = false;
            el('refreshBtn').textContent = 'Refresh';
        }
    }

    // Create form
    el('createForm').addEventListener('submit', async (ev) => {
        ev.preventDefault();
        hideMsg(el('createMsg'));

        const btn = el('createBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span>Creating';

        const payload = {
            name: el('name').value,
            email: el('email').value,
            password: el('password').value,
        };

        try {
            await apiFetch('/users', { method: 'POST', body: JSON.stringify(payload) });
            showMsg(el('createMsg'), 'ok', 'User created.');
            el('createForm').reset();
            state.page = 1;
            await loadUsers();
        } catch (e) {
            showMsg(el('createMsg'), 'err', humanizeApiError(e));
        } finally {
            btn.disabled = false;
            btn.textContent = 'Create';
        }
    });

    el('createReset').addEventListener('click', () => {
        el('createForm').reset();
        hideMsg(el('createMsg'));
    });

    // Filters
    let searchTimer = null;
    el('search').addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(async () => {
            state.search = el('search').value;
            state.page = 1;
            await loadUsers();
        }, 250);
    });
    el('sortDir').addEventListener('change', async () => {
        state.sortDir = el('sortDir').value;
        state.page = 1;
        await loadUsers();
    });
    el('perPage').addEventListener('change', async () => {
        state.perPage = Number(el('perPage').value);
        state.page = 1;
        await loadUsers();
    });
    el('refreshBtn').addEventListener('click', loadUsers);

    // Pager
    el('prevBtn').addEventListener('click', async () => {
        if (!state.last?.links?.prev) return;
        state.page = Math.max(1, state.page - 1);
        await loadUsers();
    });
    el('nextBtn').addEventListener('click', async () => {
        if (!state.last?.links?.next) return;
        state.page = state.page + 1;
        await loadUsers();
    });

    // Init
    el('apiBaseBadge').textContent = `API: ${apiBase}`;
    loadUsers();
</script>
</body>
</html>

