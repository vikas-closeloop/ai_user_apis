<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-lg font-semibold text-gray-900">User Management</h1>
                            <p class="mt-1 text-sm text-gray-500">
                                Simple UI calling your REST API at
                                <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded border border-gray-200">/api/users</span>.
                            </p>
                        </div>
                        <span id="apiBaseBadge" class="inline-flex items-center rounded-full border border-indigo-100 bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
                            API: /api
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div id="createMsg" class="hidden mb-4 text-sm rounded-md px-4 py-3" role="alert"></div>
                    <form id="createForm" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input id="name" name="name" required maxlength="255" autocomplete="name"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"/>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input id="email" name="email" required autocomplete="email"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"/>
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"/>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button id="createBtn" type="submit"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Create
                            </button>
                            <button id="createReset" type="button"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-4">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div class="flex items-center gap-3">
                            <p class="text-sm font-semibold text-gray-900">Users</p>
                            <span id="countBadge" class="inline-flex items-center rounded-full border border-gray-200 bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">
                                Loading…
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <input id="search" placeholder="Search name or email…"
                                   class="w-56 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"/>
                            <select id="sortDir"
                                    class="rounded-md border-gray-300 bg-white py-2 pl-3 pr-10 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="desc">Newest first</option>
                                <option value="asc">Oldest first</option>
                            </select>
                            <select id="perPage"
                                    class="rounded-md border-gray-300 bg-white py-2 pl-3 pr-10 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="5">5 / page</option>
                                <option value="10">10 / page</option>
                                <option value="15" selected>15 / page</option>
                                <option value="25">25 / page</option>
                                <option value="50">50 / page</option>
                            </select>
                            <button id="refreshBtn" type="button"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                Refresh
                            </button>
                        </div>
                    </div>

                    <div id="listMsg" class="hidden text-sm rounded-md px-4 py-3" role="alert"></div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500 w-20">ID</th>
                                <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                                <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500 w-48">Created</th>
                                <th class="px-3 py-2 text-right text-xs font-medium uppercase tracking-wider text-gray-500 w-64">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="tbody" class="divide-y divide-gray-200 bg-white"></tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <p id="pageInfo" class="text-xs text-gray-500">—</p>
                        <div class="flex gap-2">
                            <button id="prevBtn" type="button"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                Prev
                            </button>
                            <button id="nextBtn" type="button"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="rowTpl">
        <tr>
            <td class="px-3 py-2 font-mono text-xs text-gray-500" data-id></td>
            <td class="px-3 py-2">
                <input data-name
                       class="w-full rounded-md border-gray-200 bg-gray-50 px-2 py-1 text-sm focus:border-indigo-500 focus:bg-white focus:ring-indigo-500"/>
            </td>
            <td class="px-3 py-2">
                <input data-email
                       class="w-full rounded-md border-gray-200 bg-gray-50 px-2 py-1 text-sm focus:border-indigo-500 focus:bg-white focus:ring-indigo-500"/>
            </td>
            <td class="px-3 py-2 text-xs text-gray-500" data-created></td>
            <td class="px-3 py-2">
                <div class="flex justify-end gap-2">
                    <button class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            data-save>
                        Save
                    </button>
                    <button class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                            data-del>
                        Delete
                    </button>
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
</x-app-layout>

