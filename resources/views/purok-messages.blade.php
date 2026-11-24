@extends('layouts.purok')

@section('title', 'Messages')

@section('content')
    @php
        // Sample leader. Later, build this from the logged-in user.
        $leader = $leader ?? ['name' => 'Ana Santos', 'initials' => 'AS', 'purok' => 'Purok 3'];
        // SAMPLE DATA for the view-only prototype. Keep the same keys when you pass real data.
        // group: resident | volunteer | official
        $contacts = $contacts ?? [
            [
                'id' => 1,
                'name' => 'Maria Lopez',
                'role' => 'Resident, Zone 2',
                'group' => 'resident',
                'initials' => 'ML',
                'color' => 'green',
                'online' => true,
            ],
            [
                'id' => 2,
                'name' => 'Jose Ramirez',
                'role' => 'Resident, Zone 2',
                'group' => 'resident',
                'initials' => 'JR',
                'color' => 'yellow',
                'online' => false,
            ],
            [
                'id' => 3,
                'name' => 'Rosa Dizon',
                'role' => 'Volunteer, Level 2',
                'group' => 'volunteer',
                'initials' => 'RD',
                'color' => 'orange',
                'online' => true,
            ],
            [
                'id' => 4,
                'name' => 'Mark Villar',
                'role' => 'Volunteer, Level 1',
                'group' => 'volunteer',
                'initials' => 'MV',
                'color' => 'green',
                'online' => false,
            ],
            [
                'id' => 5,
                'name' => 'Elena Reyes',
                'role' => 'Solid Waste Officer',
                'group' => 'official',
                'initials' => 'ER',
                'color' => 'orange',
                'online' => true,
            ],
            [
                'id' => 6,
                'name' => 'Roberto Santos',
                'role' => 'Barangay Captain',
                'group' => 'official',
                'initials' => 'RS',
                'color' => 'green',
                'online' => false,
            ],
        ];

        $threads = $threads ?? [
            [
                'id' => 'announce',
                'type' => 'broadcast',
                'title' => 'Purok Announcements',
                'subtitle' => 'Messages you sent to every household',
                'unread' => 0,
                'messages' => [
                    [
                        'id' => 1,
                        'from' => 'me',
                        'title' => 'Clean-up on April 30',
                        'text' =>
                            'Please join us at the Zone 2 covered court at 6:00 AM. Gloves and bags are provided.',
                        't' => 'Apr 25, 2025',
                        'reach' => 186,
                    ],
                ],
            ],
            [
                'id' => 't1',
                'type' => 'direct',
                'contactId' => 1,
                'unread' => 1,
                'messages' => [
                    [
                        'id' => 11,
                        'from' => 'them',
                        'text' =>
                            'Good morning, Ma\'am Ana. The garbage near the creek is still there. When can it be picked up?',
                        't' => 'Apr 28, 8:02 AM',
                    ],
                ],
            ],
            [
                'id' => 't2',
                'type' => 'direct',
                'contactId' => 3,
                'unread' => 0,
                'messages' => [
                    [
                        'id' => 21,
                        'from' => 'me',
                        'text' => 'Rosa, can you lead the sorting booth on Apr 30?',
                        't' => 'Apr 26, 3:10 PM',
                    ],
                    [
                        'id' => 22,
                        'from' => 'them',
                        'text' => 'Yes, I can. I\'ll bring two more volunteers from Zone 2.',
                        't' => 'Apr 26, 3:42 PM',
                    ],
                ],
            ],
            [
                'id' => 't3',
                'type' => 'direct',
                'contactId' => 5,
                'unread' => 0,
                'messages' => [
                    [
                        'id' => 31,
                        'from' => 'me',
                        'text' =>
                            'Good afternoon. Can the Zone 4 truck pass by the creek this week? We have a big pile.',
                        't' => 'Apr 27, 1:20 PM',
                    ],
                    [
                        'id' => 32,
                        'from' => 'them',
                        'text' => 'Noted. We\'ll add it to Thursday\'s route.',
                        't' => 'Apr 27, 2:05 PM',
                    ],
                ],
            ],
        ];
    @endphp

    <div class="pl-title-row">
        <div class="page-title">
            <h1>Messages</h1>
            <p>Talk to residents, volunteers and barangay officials, or send an announcement to your whole purok.</p>
        </div>
        <button type="button" class="btn-primary" id="announceBtn"><i class="fa-solid fa-bullhorn"></i> Send
            announcement</button>
    </div>

    <section class="ms-shell" id="msShell">

        <div class="pg-card ms-list-col">
            <div class="ms-list-top">
                <div class="ms-list-title">
                    <h3>Chats</h3>
                    <button type="button" class="icon-btn" id="newMsgBtn" aria-label="Start a new message"
                        title="New message"><i class="fa-solid fa-pen-to-square"></i></button>
                </div>
                <div class="search-box block">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" id="msSearch" placeholder="Search messages" aria-label="Search messages">
                </div>
                <div class="ms-tabs" id="msTabs" role="tablist">
                    <button type="button" role="tab" data-g="all" aria-selected="true">All</button>
                    <button type="button" role="tab" data-g="resident" aria-selected="false">Residents</button>
                    <button type="button" role="tab" data-g="volunteer" aria-selected="false">Volunteers</button>
                    <button type="button" role="tab" data-g="official" aria-selected="false">Officials</button>
                </div>
            </div>
            <div class="ms-threads" id="msThreads"></div>
        </div>

        <div class="pg-card ms-chat-col" id="msChat"></div>
    </section>
@endsection

@push('scripts')
    <script>
        const CONTACTS = @json($contacts);
        let THREADS = @json($threads);
        let activeId = null,
            group = 'all',
            query = '',
            uid = 1000;

        const contact = (id) => CONTACTS.find((c) => c.id === id);
        const find = (id) => THREADS.find((t) => t.id === id);
        const live = (t) => t.messages;
        const last = (t) => t.messages[t.messages.length - 1];
        const title = (t) => t.type === 'direct' ? contact(t.contactId).name : t.title;
        const sub = (t) => t.type === 'direct' ? contact(t.contactId).role : t.subtitle;
        const groupOf = (t) => t.type === 'direct' ? contact(t.contactId).group : 'broadcast';
        const nowLabel = () => new Date().toLocaleTimeString([], {
            hour: 'numeric',
            minute: '2-digit'
        });
        const reEsc = (s) => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const hl = (text) => {
            const s = esc(text);
            return query ? s.replace(new RegExp('(' + reEsc(esc(query)) + ')', 'gi'), '<mark>$1</mark>') : s;
        };

        function avatar(t, lg) {
            const c = lg ? 'av av-lg' : 'av';
            if (t.type === 'broadcast') return '<span class="' + c +
                ' av-orange"><i class="fa-solid fa-bullhorn"></i></span>';
            const k = contact(t.contactId);
            return '<span class="' + c + ' av-' + k.color + '">' + esc(k.initials) + (k.online ?
                '<i class="dot-online"></i>' : '') + '</span>';
        }

        function matches(t) {
            if (group !== 'all' && groupOf(t) !== group) return false;
            if (!query) return true;
            const q = query.toLowerCase();
            return title(t).toLowerCase().includes(q) || t.messages.some((m) => ((m.title || '') + ' ' + m.text)
                .toLowerCase().includes(q));
        }

        function renderList() {
            const items = THREADS.filter(matches);
            if (!items.length) {
                $('msThreads').innerHTML = '<div class="empty-state"><i class="fa-regular fa-comment-dots"></i><p>' + (
                    query ? 'No messages match "' + esc(query) + '".' : 'No conversations here yet.') + '</p></div>';
                return;
            }
            $('msThreads').innerHTML = items.map((t) => {
                const m = last(t);
                return '<button type="button" class="thread' + (t.id === activeId ? ' is-active' : '') + (t.unread ?
                        ' is-unread' : '') + '" data-thread="' + t.id + '">' + avatar(t) +
                    '<span class="thread-body"><span class="thread-top"><span class="thread-name">' + hl(title(t)) +
                    '</span><span class="thread-time">' + esc(m ? m.t.split(',')[0] : '') + '</span></span>' +
                    '<span class="thread-snippet">' + hl(m ? (m.from === 'me' ? 'You: ' : '') + (m.title || m
                        .text) : 'No messages yet') + '</span></span>' +
                    (t.unread ? '<span class="unread-badge">' + t.unread + '</span>' : '') + '</button>';
            }).join('');
        }

        function renderChat() {
            const t = find(activeId);
            if (!t) {
                $('msChat').innerHTML =
                    '<div class="chat-empty"><i class="fa-regular fa-comments"></i><h3>Select a conversation</h3><p>Pick someone from the list, start a new message, or send an announcement to the purok.</p>' +
                    '<button type="button" class="btn-primary" data-new><i class="fa-solid fa-pen-to-square"></i> New message</button></div>';
                return;
            }
            const body = t.messages.map((m) => {
                if (t.type === 'broadcast') {
                    return '<article class="post"><h4>' + hl(m.title) + '</h4><p>' + hl(m.text) +
                        '</p><time><i class="fa-regular fa-calendar"></i> ' + esc(m.t) +
                        ' &middot; <i class="fa-solid fa-house"></i> Sent to ' + m.reach +
                        ' households</time></article>';
                }
                const mine = m.from === 'me';
                return '<div class="b-row ' + (mine ? 'me' : 'them') + '"><div class="bubble">' + hl(m.text)
                    .replace(/\n/g, '<br>') + '<span class="b-meta">' + esc(m.t) + '</span></div></div>';
            }).join('');

            const footer = t.type === 'broadcast' ?
                '<div class="chat-note"><i class="fa-solid fa-bullhorn"></i> Announcements go to every household in your purok. <button type="button" class="link-btn" id="announceInline">Write a new one</button></div>' :
                '<form class="composer" id="composer"><textarea id="composerInput" rows="1" placeholder="Write a message" aria-label="Write a message"></textarea><button type="submit" class="send-btn" aria-label="Send"><i class="fa-solid fa-paper-plane"></i></button></form>';

            $('msChat').innerHTML =
                '<header class="chat-head"><button type="button" class="icon-btn back-btn" data-back aria-label="Back to chats"><i class="fa-solid fa-chevron-left"></i></button>' +
                avatar(t, true) + '<div class="chat-who"><h3>' + esc(title(t)) + '</h3><p>' + esc(sub(t)) +
                '</p></div></header>' +
                '<div class="chat-body' + (t.type === 'broadcast' ? ' is-feed' : '') + '" id="chatBody">' + body +
                '</div>' + footer;
            const cb = $('chatBody');
            cb.scrollTop = cb.scrollHeight;
        }

        function renderAll() {
            renderList();
            renderChat();
            $('msShell').classList.toggle('show-chat', !!activeId);
        }

        function openThread(id) {
            activeId = id;
            const t = find(id);
            if (t) t.unread = 0;
            renderAll();
        }

        function send(t, text) {
            t.messages.push({
                id: ++uid,
                from: 'me',
                text,
                t: nowLabel()
            });
            THREADS = [t].concat(THREADS.filter((x) => x !== t));
        }

        // ---------- Modals ----------
        function openNew() {
            const groups = [
                ['resident', 'Residents'],
                ['volunteer', 'Volunteers'],
                ['official', 'Barangay officials']
            ];
            PL.openModal(
                '<h3>New message</h3><p class="modal-sub">Choose who to message.</p><div class="pick-list" role="radiogroup" aria-label="Contact">' +
                groups.map((g) => '<p class="pick-group">' + g[1] + '</p>' + CONTACTS.filter((c) => c.group === g[0])
                    .map((c) =>
                        '<button type="button" role="radio" class="pick" aria-checked="false" data-pick="' + c.id +
                        '"><span class="av av-' + c.color + '">' + esc(c.initials) + '</span>' +
                        '<span class="pick-text"><strong>' + esc(c.name) + '</strong><small>' + esc(c.role) +
                        '</small></span><i class="fa-solid fa-circle-check"></i></button>').join('')).join('') +
                '</div><textarea id="newText" rows="3" placeholder="Write your message"></textarea>' +
                '<div class="modal-actions"><button type="button" class="btn-ghost" data-modal-close>Cancel</button><button type="button" class="btn-primary" id="newSend" disabled><i class="fa-solid fa-paper-plane"></i> Send</button></div>'
                );
        }

        function openAnnounce() {
            PL.openModal('<h3>Send announcement</h3><p class="modal-sub">Every household in <strong>' + esc(document
                    .querySelector('[data-purok]').textContent) + '</strong> will see this.</p>' +
                '<div class="field"><label for="aTitle">Title</label><input id="aTitle" type="text" placeholder="e.g. Clean-up this Saturday"></div>' +
                '<div class="field"><label for="aText">Message</label><textarea id="aText" rows="4" placeholder="Write your announcement"></textarea></div>' +
                '<div class="modal-actions"><button type="button" class="btn-ghost" data-modal-close>Cancel</button><button type="button" class="btn-primary" id="aSend"><i class="fa-solid fa-bullhorn"></i> Send to purok</button></div>'
                );
        }

        let picked = null;

        function checkNew() {
            const b = $('newSend');
            if (b) b.disabled = !(picked && $('newText').value.trim());
        }

        // ---------- Events ----------
        $('msThreads').addEventListener('click', (e) => {
            const b = e.target.closest('[data-thread]');
            if (b) openThread(b.dataset.thread);
        });
        $('msTabs').addEventListener('click', (e) => {
            const b = e.target.closest('[data-g]');
            if (!b) return;
            group = b.dataset.g;
            document.querySelectorAll('#msTabs button').forEach((x) => x.setAttribute('aria-selected', x === b));
            renderList();
        });
        $('msSearch').addEventListener('input', (e) => {
            query = e.target.value.trim();
            renderList();
            renderChat();
        });
        $('newMsgBtn').addEventListener('click', () => {
            picked = null;
            openNew();
        });
        $('announceBtn').addEventListener('click', openAnnounce);

        $('msChat').addEventListener('click', (e) => {
            if (e.target.closest('[data-new]')) {
                picked = null;
                openNew();
            }
            if (e.target.closest('[data-back]')) {
                activeId = null;
                renderAll();
            }
            if (e.target.closest('#announceInline')) openAnnounce();
        });
        $('msChat').addEventListener('keydown', (e) => {
            if (e.target.id === 'composerInput' && e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                $('composer').requestSubmit();
            }
        });
        $('msChat').addEventListener('input', (e) => {
            if (e.target.id === 'composerInput') {
                e.target.style.height = 'auto';
                e.target.style.height = Math.min(e.target.scrollHeight, 120) + 'px';
            }
        });
        $('msChat').addEventListener('submit', (e) => {
            e.preventDefault();
            const text = $('composerInput').value.trim();
            if (!text) return;
            send(find(activeId), text);
            renderAll();
            $('composerInput').focus();
        });

        $('modalDialog').addEventListener('click', (e) => {
            const pick = e.target.closest('[data-pick]');
            if (pick) {
                picked = Number(pick.dataset.pick);
                document.querySelectorAll('.pick').forEach((p) => {
                    p.classList.toggle('is-picked', p === pick);
                    p.setAttribute('aria-checked', p === pick);
                });
                return checkNew();
            }
            if (e.target.closest('#newSend')) {
                let t = THREADS.find((x) => x.type === 'direct' && x.contactId === picked);
                if (!t) {
                    t = {
                        id: 't' + (++uid),
                        type: 'direct',
                        contactId: picked,
                        unread: 0,
                        messages: []
                    };
                    THREADS.unshift(t);
                }
                send(t, $('newText').value.trim());
                PL.closeModal();
                group = 'all';
                document.querySelectorAll('#msTabs button').forEach((x) => x.setAttribute('aria-selected', x.dataset
                    .g === 'all'));
                openThread(t.id);
                PL.toast('Message sent to ' + contact(picked).name);
            }
            if (e.target.closest('#aSend')) {
                const ttl = $('aTitle').value.trim(),
                    txt = $('aText').value.trim();
                $('aTitle').classList.toggle('invalid', !ttl);
                $('aText').classList.toggle('invalid', !txt);
                if (!ttl || !txt) return;
                const t = find('announce');
                t.messages.push({
                    id: ++uid,
                    from: 'me',
                    title: ttl,
                    text: txt,
                    t: 'Just now',
                    reach: 186
                });
                THREADS = [t].concat(THREADS.filter((x) => x !== t));
                PL.closeModal();
                group = 'all';
                openThread('announce');
                PL.toast('Announcement sent to 186 households');
            }
        });
        $('modalDialog').addEventListener('input', (e) => {
            if (e.target.id === 'newText') checkNew();
        });

        if (window.matchMedia('(min-width: 769px)').matches) {
            const f = THREADS.find((t) => t.type === 'direct');
            if (f) {
                activeId = f.id;
                f.unread = 0;
            }
        }
        renderAll();
    </script>
@endpush
