@php
  // ---------------------------------------------------------------------------
  // SAMPLE DATA for the view-only stage. Names and messages are placeholders.
  // Later, pass $officials and $threads from your controller (only the logged-in
  // resident's own conversations) and delete this block. Keep the same keys.
  //   officials : barangay officials / admins a resident is allowed to contact
  //   threads   : type = 'official' | 'verification' | 'news'
  //   message   : from = 'me' | 'them' | 'system'
  // ---------------------------------------------------------------------------
  $officials = $officials ?? [
    ['id' => 1, 'name' => 'Roberto Santos',  'role' => 'Barangay Captain',        'initials' => 'RS', 'color' => 'green',  'online' => true],
    ['id' => 2, 'name' => 'Elena Reyes',     'role' => 'Solid Waste Officer',     'initials' => 'ER', 'color' => 'orange', 'online' => true],
    ['id' => 3, 'name' => 'Carlo Mendoza',   'role' => 'Zone 2 Patrol Lead',      'initials' => 'CM', 'color' => 'yellow', 'online' => false],
    ['id' => 4, 'name' => 'Teresa Villanueva','role' => 'Kagawad, Environment',   'initials' => 'TV', 'color' => 'green',  'online' => false],
    ['id' => 5, 'name' => 'Jun Dela Cruz',   'role' => 'Barangay Secretary',      'initials' => 'JD', 'color' => 'orange', 'online' => true],
  ];

  $threads = $threads ?? [
    [
      'id' => 'verification', 'type' => 'verification', 'title' => 'Verification', 'subtitle' => 'Automatic updates about your reports and account',
      'unread' => 1,
      'messages' => [
        ['id' => 1, 'from' => 'system', 'tag' => 'account',  'title' => 'Account verified',         'text' => 'Your resident account has been verified. You can now submit waste reports.', 't' => 'Mar 12, 2025'],
        ['id' => 2, 'from' => 'system', 'tag' => 'verified', 'title' => 'Report BB-0001 verified',   'text' => 'Your report "Illegal dumping" in Zone 3 was verified by the barangay team.', 't' => 'Apr 16, 2025'],
        ['id' => 3, 'from' => 'system', 'tag' => 'resolved', 'title' => 'Report BB-0001 resolved',   'text' => 'The area has been cleared. Thank you for reporting.', 't' => 'Apr 18, 2025'],
        ['id' => 4, 'from' => 'system', 'tag' => 'verified', 'title' => 'Report BB-0002 verified',   'text' => 'Your report "Broken trash bin" in Zone 1 was verified.', 't' => 'Apr 23, 2025'],
        ['id' => 5, 'from' => 'system', 'tag' => 'resolved', 'title' => 'Report BB-0002 resolved',   'text' => 'The bin was replaced. Thank you for helping keep the community clean.', 't' => 'Apr 24, 2025'],
        ['id' => 6, 'from' => 'system', 'tag' => 'received', 'title' => 'Report BB-0003 received',   'text' => 'Your report "Plastic waste on the sidewalk" is waiting for verification.', 't' => 'Apr 27, 2025'],
      ],
    ],
    [
      'id' => 'news', 'type' => 'news', 'title' => 'Barangay News', 'subtitle' => 'Announcements from the barangay',
      'unread' => 0, 'subscribed' => false,
      'messages' => [
        ['id' => 11, 'from' => 'them', 'title' => 'Collection schedule during Holy Week', 'text' => 'General waste collection will move to Wednesday this week. Recyclables and biodegradable pickups are unchanged.', 't' => 'Apr 14, 2025'],
        ['id' => 12, 'from' => 'them', 'title' => 'Community Clean-up on April 30',          'text' => 'Join us in Zone 2 at 6:00 AM. Gloves and bags will be provided. Bring your own water bottle.', 't' => 'Apr 25, 2025'],
        ['id' => 13, 'from' => 'them', 'title' => 'Reminder: segregate your waste',           'text' => 'Mixed bags may be left uncollected. Please separate biodegradable, recyclable and general waste.', 't' => 'Apr 26, 2025'],
      ],
    ],
    [
      'id' => 't2', 'type' => 'official', 'officialId' => 2, 'unread' => 1,
      'messages' => [
        ['id' => 21, 'from' => 'me',   'text' => 'Good morning! The recyclables truck skipped our street last Tuesday. Is there a make-up pickup?', 't' => 'Apr 23, 9:12 AM'],
        ['id' => 22, 'from' => 'them', 'text' => 'Good morning, Maria. Thanks for letting us know. The truck had a breakdown that day.', 't' => 'Apr 23, 9:40 AM'],
        ['id' => 23, 'from' => 'them', 'text' => 'We will do a make-up pickup this Friday morning. Please keep your recyclables out until then.', 't' => 'Apr 23, 9:41 AM'],
      ],
    ],
    [
      'id' => 't3', 'type' => 'official', 'officialId' => 3, 'unread' => 0,
      'messages' => [
        ['id' => 31, 'from' => 'me',   'text' => 'Hello, there is a pile of plastic near the corner store again. I already filed a report.', 't' => 'Apr 27, 8:20 AM'],
        ['id' => 32, 'from' => 'them', 'text' => 'Noted. My team will check the area during tomorrow\'s patrol.', 't' => 'Apr 27, 10:05 AM'],
      ],
    ],
  ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Bantay Basura - Messages</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/user-dash.css', 'resources/css/user-messages.css', 'resources/css/custom.css'])
</head>
<body>
    @include('partials.sidebar')

  <div class="main-wrapper">

    {{-- Top Navigation Header --}}
    <header class="top-header">
      <div class="user-greeting">
        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=120" alt="Maria Lopez" class="avatar-img" onerror="this.src='https://placehold.co/80x80/1e3a17/fff9a2?text=ML'">
        <div class="greeting-text">
          <h2>Hello, Maria Lopez!</h2>
        </div>
      </div>

      <div class="top-actions">
        <button class="notification-btn" aria-label="Notifications">
          <i class="fa-regular fa-bell"></i>
          <span class="notification-dot"></span>
        </button>

        <div class="profile-pill">
          <div class="avatar-initials">ML</div>
          <div class="profile-info">
            <div class="name">Maria Lopez</div>
            <div class="role">Resident</div>
          </div>
          <i class="fa-solid fa-chevron-down dropdown-icon"></i>
        </div>
      </div>
    </header>

    <main class="dashboard-content">

      <div class="page-title">
        <h1>Messages</h1>
        <p>Chat with barangay officials, read announcements and see updates on your reports.</p>
      </div>

      {{-- ================= Officials strip ================= --}}
      <section class="msg-card officials-card">
        <div class="officials-head">
          <h3>Barangay officials</h3>
          <span>Only officials can be contacted</span>
        </div>
        <div class="officials-strip" id="officialsStrip"></div>
      </section>

      {{-- ================= Inbox ================= --}}
      <section class="msg-shell" id="msgShell">

        {{-- List column --}}
        <div class="msg-card msg-list-col">
          <div class="list-top">
            <div class="list-title">
              <h3>Chats</h3>
              <button type="button" class="icon-btn" id="newConvoBtn" aria-label="Start a new conversation" title="New conversation">
                <i class="fa-solid fa-pen-to-square"></i>
              </button>
            </div>

            <div class="search-box">
              <i class="fa-solid fa-magnifying-glass"></i>
              <input type="search" id="searchInput" placeholder="Search messages" aria-label="Search messages">
              <button type="button" id="searchClear" class="search-clear" aria-label="Clear search" hidden><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="filter-tabs" role="tablist" id="filterTabs">
              <button type="button" role="tab" data-filter="all" aria-selected="true">All</button>
              <button type="button" role="tab" data-filter="official" aria-selected="false">Officials</button>
              <button type="button" role="tab" data-filter="verification" aria-selected="false">Verification</button>
              <button type="button" role="tab" data-filter="news" aria-selected="false">News</button>
            </div>
          </div>

          <div class="thread-list" id="threadList"></div>
        </div>

        {{-- Chat column --}}
        <div class="msg-card msg-chat-col" id="chatCol"></div>
      </section>

    </main>
  </div>

  {{-- Modal (new conversation / delete confirmation) --}}
  <div class="modal" id="modal" hidden>
    <div class="modal-backdrop" data-close></div>
    <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalTitle" id="modalDialog"></div>
  </div>

  <div class="toast" id="toast" role="status" aria-live="polite" hidden></div>

  <script>
    // Mobile menu
    document.getElementById('menuToggle').addEventListener('click', function () {
      document.getElementById('navList').classList.toggle('active');
    });

    // =========================================================================
    // Messages (all changes live in this page only until you add a backend)
    // =========================================================================
    const OFFICIALS = @json($officials);
    let THREADS = @json($threads);

    const $ = (id) => document.getElementById(id);
    const esc = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const reEsc = (s) => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const nowLabel = () => new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
    const off = (id) => OFFICIALS.find((o) => o.id === id);
    const TAGS = {
      account:  { label: 'Account',  icon: 'fa-solid fa-user-check' },
      verified: { label: 'Verified', icon: 'fa-solid fa-circle-check' },
      resolved: { label: 'Resolved', icon: 'fa-solid fa-broom' },
      received: { label: 'Received', icon: 'fa-regular fa-hourglass-half' },
    };

    let activeId = null, filter = 'all', query = '', editingId = null, uid = 1000, newTarget = null;

    // ---------- Helpers ----------
    const title = (t) => t.type === 'official' ? off(t.officialId).name : t.title;
    const sub = (t) => t.type === 'official' ? off(t.officialId).role : t.subtitle;
    const live = (t) => t.messages.filter((m) => !m.deleted);
    const lastMsg = (t) => live(t).slice(-1)[0];
    const find = (id) => THREADS.find((t) => t.id === id);

    function avatar(t, size) {
      const cls = size === 'lg' ? 'av av-lg' : 'av';
      if (t.type === 'verification') return '<span class="' + cls + ' av-green"><i class="fa-solid fa-shield-halved"></i></span>';
      if (t.type === 'news') return '<span class="' + cls + ' av-orange"><i class="fa-solid fa-bullhorn"></i></span>';
      const o = off(t.officialId);
      return '<span class="' + cls + ' av-' + o.color + '">' + esc(o.initials) + (o.online ? '<i class="dot-online"></i>' : '') + '</span>';
    }

    function hl(text) {
      const safe = esc(text);
      if (!query) return safe;
      return safe.replace(new RegExp('(' + reEsc(esc(query)) + ')', 'gi'), '<mark>$1</mark>');
    }

    function toast(msg) {
      const el = $('toast');
      el.textContent = msg;
      el.hidden = false;
      clearTimeout(toast.t);
      toast.t = setTimeout(() => { el.hidden = true; }, 2600);
    }

    // ---------- Officials strip ----------
    function renderStrip() {
      let html = '<button type="button" class="off-item" data-new="">'
        + '<span class="av av-new"><i class="fa-solid fa-plus"></i></span><span class="off-name">New message</span><span class="off-role">&nbsp;</span></button>';
      OFFICIALS.forEach((o) => {
        html += '<button type="button" class="off-item" data-official="' + o.id + '">'
          + '<span class="av av-' + o.color + '">' + esc(o.initials) + (o.online ? '<i class="dot-online"></i>' : '') + '</span>'
          + '<span class="off-name">' + esc(o.name.split(' ')[0]) + '</span>'
          + '<span class="off-role">' + esc(o.role) + '</span></button>';
      });
      $('officialsStrip').innerHTML = html;
    }

    // ---------- Thread list ----------
    function matches(t) {
      if (filter !== 'all' && t.type !== filter) return false;
      if (!query) return true;
      const q = query.toLowerCase();
      if (title(t).toLowerCase().includes(q) || sub(t).toLowerCase().includes(q)) return true;
      return live(t).some((m) => ((m.title || '') + ' ' + (m.text || '')).toLowerCase().includes(q));
    }

    function snippetFor(t) {
      if (query) {
        const q = query.toLowerCase();
        const hit = live(t).slice().reverse().find((m) => ((m.title || '') + ' ' + (m.text || '')).toLowerCase().includes(q));
        if (hit) return hit.title && hit.title.toLowerCase().includes(q) ? hit.title : hit.text;
      }
      const m = lastMsg(t);
      if (!m) return 'No messages yet';
      return (m.from === 'me' ? 'You: ' : '') + (m.title || m.text);
    }

    function renderList() {
      const items = THREADS.filter(matches);
      if (!items.length) {
        $('threadList').innerHTML = '<div class="list-empty"><i class="fa-regular fa-comment-dots"></i><p>'
          + (query ? 'No messages match "' + esc(query) + '".' : 'No conversations here yet.') + '</p></div>';
        return;
      }
      $('threadList').innerHTML = items.map((t) => {
        const m = lastMsg(t);
        return '<button type="button" class="thread' + (t.id === activeId ? ' is-active' : '') + (t.unread ? ' is-unread' : '') + '" data-thread="' + t.id + '">'
          + avatar(t)
          + '<span class="thread-body"><span class="thread-top"><span class="thread-name">' + hl(title(t)) + '</span><span class="thread-time">' + esc(m ? m.t.split(',')[0] : '') + '</span></span>'
          + '<span class="thread-snippet">' + hl(snippetFor(t)) + '</span></span>'
          + (t.unread ? '<span class="unread-badge">' + t.unread + '</span>' : '')
          + '</button>';
      }).join('');
    }

    // ---------- Chat pane ----------
    function renderMessage(t, m) {
      if (t.type === 'verification') {
        const tag = TAGS[m.tag] || TAGS.received;
        return '<article class="sys-msg tag-' + m.tag + '"><span class="sys-icon"><i class="' + tag.icon + '"></i></span>'
          + '<div><div class="sys-top"><strong>' + hl(m.title) + '</strong><span class="sys-tag">' + tag.label + '</span></div>'
          + '<p>' + hl(m.text) + '</p><time>' + esc(m.t) + '</time></div></article>';
      }
      if (t.type === 'news') {
        return '<article class="news-post"><h4>' + hl(m.title) + '</h4><p>' + hl(m.text) + '</p><time><i class="fa-regular fa-calendar"></i> ' + esc(m.t) + '</time></article>';
      }
      const mine = m.from === 'me';
      if (m.deleted) {
        return '<div class="b-row ' + (mine ? 'me' : 'them') + '"><div class="bubble is-removed">' + (mine ? 'You removed a message' : 'Message removed') + '</div></div>';
      }
      if (mine && editingId === m.id) {
        return '<div class="b-row me"><div class="edit-box"><textarea id="editInput" rows="2" aria-label="Edit message">' + esc(m.text) + '</textarea>'
          + '<div class="edit-actions"><button type="button" class="btn-cancel" data-edit-cancel>Cancel</button><button type="button" class="btn-save" data-edit-save="' + m.id + '">Save</button></div></div></div>';
      }
      return '<div class="b-row ' + (mine ? 'me' : 'them') + '">'
        + (mine ? '<div class="b-actions"><button type="button" data-edit="' + m.id + '" aria-label="Edit message" title="Edit"><i class="fa-solid fa-pen"></i></button>'
          + '<button type="button" data-unsend="' + m.id + '" aria-label="Remove message" title="Remove"><i class="fa-regular fa-trash-can"></i></button></div>' : '')
        + '<div class="bubble">' + hl(m.text).replace(/\n/g, '<br>') + '<span class="b-meta">' + esc(m.t) + (m.edited ? ' &middot; edited' : '') + '</span></div></div>';
    }

    function renderChat() {
      const col = $('chatCol');
      const t = find(activeId);
      if (!t) {
        col.innerHTML = '<div class="chat-empty"><i class="fa-regular fa-comments"></i><h3>Select a conversation</h3><p>Pick someone from the list or start a new message to an official.</p>'
          + '<button type="button" class="btn-primary" data-new=""><i class="fa-solid fa-pen-to-square"></i> New message</button></div>';
        return;
      }

      let actions = '';
      if (t.type === 'official') actions = '<button type="button" class="icon-btn danger" data-delete-convo aria-label="Delete conversation" title="Delete conversation"><i class="fa-regular fa-trash-can"></i></button>';
      if (t.type === 'news') {
        actions = '<button type="button" class="btn-sub' + (t.subscribed ? ' is-on' : '') + '" data-subscribe aria-pressed="' + !!t.subscribed + '">'
          + '<i class="' + (t.subscribed ? 'fa-solid fa-bell' : 'fa-regular fa-bell') + '"></i> ' + (t.subscribed ? 'Subscribed' : 'Subscribe') + '</button>';
      }
      const status = t.type === 'official' ? (off(t.officialId).online ? '<span class="status on">Active now</span> &middot; ' : '') : '';

      let footer = '';
      if (t.type === 'official') {
        footer = '<form class="composer" id="composer"><textarea id="composerInput" rows="1" placeholder="Write a message" aria-label="Write a message"></textarea>'
          + '<button type="submit" class="send-btn" aria-label="Send"><i class="fa-solid fa-paper-plane"></i></button></form>';
      } else if (t.type === 'verification') {
        footer = '<div class="chat-note"><i class="fa-solid fa-lock"></i> These are automatic messages. You can\'t reply here.</div>';
      } else {
        footer = '<div class="chat-note">' + (t.subscribed
          ? '<i class="fa-solid fa-bell"></i> You\'ll be notified when the barangay posts news.'
          : '<i class="fa-regular fa-bell-slash"></i> Subscribe to get notified about new announcements.') + '</div>';
      }

      const body = live(t).length || t.messages.length
        ? t.messages.map((m) => renderMessage(t, m)).join('')
        : '<div class="chat-empty small"><p>No messages yet. Say hello!</p></div>';

      col.innerHTML = '<header class="chat-head"><button type="button" class="icon-btn back-btn" data-back aria-label="Back to chats"><i class="fa-solid fa-chevron-left"></i></button>'
        + avatar(t, 'lg')
        + '<div class="chat-who"><h3>' + esc(title(t)) + '</h3><p>' + status + esc(sub(t)) + '</p></div><div class="chat-actions">' + actions + '</div></header>'
        + '<div class="chat-body' + (t.type === 'official' ? '' : ' is-feed') + '" id="chatBody">' + body + '</div>' + footer;

      const cb = $('chatBody');
      if (editingId) { const ei = $('editInput'); if (ei) { ei.focus(); ei.setSelectionRange(ei.value.length, ei.value.length); } }
      else cb.scrollTop = cb.scrollHeight;
    }

    function renderAll() {
      renderList();
      renderChat();
      $('msgShell').classList.toggle('show-chat', !!activeId);
    }

    // ---------- Actions ----------
    function openThread(id) {
      activeId = id;
      editingId = null;
      const t = find(id);
      if (t) t.unread = 0;
      renderAll();
    }

    function sendMessage(t, text) {
      t.messages.push({ id: ++uid, from: 'me', text, t: nowLabel() });
      THREADS = [t].concat(THREADS.filter((x) => x !== t)); // newest chat moves to top
    }

    // ---------- Modal ----------
    function openModal(html) {
      $('modalDialog').innerHTML = html;
      $('modal').hidden = false;
      const f = $('modalDialog').querySelector('textarea, button:not([data-close])');
      if (f) f.focus();
    }
    const closeModal = () => { $('modal').hidden = true; $('modalDialog').innerHTML = ''; };

    function openNewConvo(preselect) {
      newTarget = preselect || null;
      openModal('<h3 id="modalTitle">New message</h3><p class="modal-sub">Choose an official to contact.</p>'
        + '<div class="pick-list" role="radiogroup" aria-label="Official">'
        + OFFICIALS.map((o) => '<button type="button" role="radio" class="pick' + (o.id === newTarget ? ' is-picked' : '') + '" aria-checked="' + (o.id === newTarget) + '" data-pick="' + o.id + '">'
          + '<span class="av av-' + o.color + '">' + esc(o.initials) + '</span><span class="pick-text"><strong>' + esc(o.name) + '</strong><small>' + esc(o.role) + '</small></span><i class="fa-solid fa-circle-check"></i></button>').join('')
        + '</div><textarea id="newText" rows="3" placeholder="Write your first message" aria-label="First message"></textarea>'
        + '<div class="modal-actions"><button type="button" class="btn-cancel" data-close>Cancel</button><button type="button" class="btn-primary" id="newSend" disabled><i class="fa-solid fa-paper-plane"></i> Send</button></div>');
    }

    function updateNewSend() {
      const btn = $('newSend');
      if (btn) btn.disabled = !(newTarget && $('newText').value.trim());
    }

    function openDeleteConfirm() {
      const t = find(activeId);
      openModal('<h3 id="modalTitle">Delete this conversation?</h3><p class="modal-sub">Your chat with ' + esc(title(t)) + ' will be removed from your inbox. This can\'t be undone.</p>'
        + '<div class="modal-actions"><button type="button" class="btn-cancel" data-close>Keep it</button><button type="button" class="btn-danger" id="confirmDelete"><i class="fa-regular fa-trash-can"></i> Delete</button></div>');
    }

    // ---------- Events ----------
    $('officialsStrip').addEventListener('click', (e) => {
      const b = e.target.closest('.off-item');
      if (!b) return;
      if (b.hasAttribute('data-new')) return openNewConvo(null);
      const id = Number(b.dataset.official);
      const existing = THREADS.find((t) => t.type === 'official' && t.officialId === id);
      existing ? openThread(existing.id) : openNewConvo(id);
    });

    $('newConvoBtn').addEventListener('click', () => openNewConvo(null));

    $('threadList').addEventListener('click', (e) => {
      const b = e.target.closest('[data-thread]');
      if (b) openThread(b.dataset.thread);
    });

    $('filterTabs').addEventListener('click', (e) => {
      const b = e.target.closest('[data-filter]');
      if (!b) return;
      filter = b.dataset.filter;
      document.querySelectorAll('#filterTabs button').forEach((x) => x.setAttribute('aria-selected', x === b));
      renderList();
    });

    $('searchInput').addEventListener('input', (e) => {
      query = e.target.value.trim();
      $('searchClear').hidden = !query;
      renderList();
      renderChat();
    });
    $('searchClear').addEventListener('click', () => {
      $('searchInput').value = '';
      query = '';
      $('searchClear').hidden = true;
      renderList();
      renderChat();
      $('searchInput').focus();
    });

    $('chatCol').addEventListener('click', (e) => {
      const t = find(activeId);
      const q = (sel) => e.target.closest(sel);
      if (q('[data-new]')) return openNewConvo(null);
      if (q('[data-back]')) { activeId = null; return renderAll(); }
      if (q('[data-delete-convo]')) return openDeleteConfirm();
      if (q('[data-subscribe]')) {
        t.subscribed = !t.subscribed;
        toast(t.subscribed ? 'Subscribed to Barangay News' : 'Unsubscribed from Barangay News');
        return renderChat();
      }
      const edit = q('[data-edit]');
      if (edit) { editingId = Number(edit.dataset.edit); return renderChat(); }
      if (q('[data-edit-cancel]')) { editingId = null; return renderChat(); }
      const save = q('[data-edit-save]');
      if (save) return saveEdit(Number(save.dataset.editSave));
      const un = q('[data-unsend]');
      if (un) {
        const m = t.messages.find((x) => x.id === Number(un.dataset.unsend));
        m.deleted = true;
        renderList();
        renderChat();
      }
    });

    function saveEdit(id) {
      const t = find(activeId);
      const m = t.messages.find((x) => x.id === id);
      const val = $('editInput').value.trim();
      if (!val) return;
      if (val !== m.text) { m.text = val; m.edited = true; }
      editingId = null;
      renderList();
      renderChat();
    }

    $('chatCol').addEventListener('keydown', (e) => {
      if (e.target.id === 'editInput') {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); saveEdit(editingId); }
        if (e.key === 'Escape') { editingId = null; renderChat(); }
      }
      if (e.target.id === 'composerInput' && e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        $('composer').requestSubmit();
      }
    });

    $('chatCol').addEventListener('input', (e) => {
      if (e.target.id === 'composerInput') {
        e.target.style.height = 'auto';
        e.target.style.height = Math.min(e.target.scrollHeight, 120) + 'px';
      }
    });

    $('chatCol').addEventListener('submit', (e) => {
      e.preventDefault();
      const input = $('composerInput');
      const text = input.value.trim();
      if (!text) return;
      sendMessage(find(activeId), text);
      renderAll();
      $('composerInput').focus();
    });

    // Modal events
    $('modal').addEventListener('click', (e) => {
      if (e.target.closest('[data-close]')) return closeModal();
      const pick = e.target.closest('[data-pick]');
      if (pick) {
        newTarget = Number(pick.dataset.pick);
        document.querySelectorAll('.pick').forEach((p) => {
          const on = p === pick;
          p.classList.toggle('is-picked', on);
          p.setAttribute('aria-checked', on);
        });
        return updateNewSend();
      }
      if (e.target.closest('#newSend')) {
        const text = $('newText').value.trim();
        let t = THREADS.find((x) => x.type === 'official' && x.officialId === newTarget);
        if (!t) {
          t = { id: 't' + (++uid), type: 'official', officialId: newTarget, unread: 0, messages: [] };
          THREADS.unshift(t);
        }
        sendMessage(t, text);
        closeModal();
        filter = 'all';
        document.querySelectorAll('#filterTabs button').forEach((x) => x.setAttribute('aria-selected', x.dataset.filter === 'all'));
        openThread(t.id);
        return toast('Message sent to ' + off(newTarget).name);
      }
      if (e.target.closest('#confirmDelete')) {
        THREADS = THREADS.filter((x) => x.id !== activeId);
        activeId = null;
        closeModal();
        renderAll();
        toast('Conversation deleted');
      }
    });

    $('modal').addEventListener('input', (e) => { if (e.target.id === 'newText') updateNewSend(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !$('modal').hidden) closeModal(); });

    // ---------- Start ----------
    renderStrip();
    if (window.matchMedia('(min-width: 769px)').matches) {
      const first = THREADS.find((t) => t.type === 'official') || THREADS[0];
      activeId = first ? first.id : null;
      if (first) first.unread = 0;
    }
    renderAll();
  </script>
</body>
</html>
