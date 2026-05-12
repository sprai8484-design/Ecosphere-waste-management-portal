
// ===== DATA =====
const ideas = [
  {
    id: 1, emoji: '👖', bg: 'linear-gradient(135deg,#6b5a4e,#9e8870)',
    title: 'Denim Jeans to Tote Bag', category: 'Clothes', difficulty: 'easy',
    time: '45 min', likes: 284, saved: 97, comments: 42,
    desc: 'Transform your worn-out jeans into a sturdy, stylish everyday tote bag — no sewing machine needed.',
    author: 'Priya S.', authorColor: '#4a8c5c', trending: true,
    materials: ['Old jeans', 'Scissors', 'Rope/handles', 'Needle & thread', 'Marker'],
    steps: [
      { title: 'Prepare the Jeans', desc: 'Lay your jeans flat and cut off both legs about 2cm below the crotch seam.', tip: 'Use sharp fabric scissors for clean edges.' },
      { title: 'Sew the Bottom', desc: 'Turn the jeans inside out. Sew a straight seam across the bottom where you cut to create the bag base.', tip: 'Use double stitching for extra durability.' },
      { title: 'Create the Handles', desc: 'Cut two strips from the jean legs (each ~60cm long, 4cm wide). Fold lengthwise and sew the edges to make strong handles.' },
      { title: 'Attach the Handles', desc: 'Mark handle positions on the waistband. Sew each handle securely with an X-box stitch pattern.' },
      { title: 'Decorate & Personalize', desc: 'Add patches, fabric paint, or embroidery to make it uniquely yours. Let dry completely before use.' }
    ]
  },
  {
    id: 2, emoji: '🍶', bg: 'linear-gradient(135deg,#3d6b8c,#5a9ec4)',
    title: 'Plastic Bottle Wall Planter', category: 'Plastic', difficulty: 'easy',
    time: '30 min', likes: 193, saved: 76, comments: 28,
    desc: 'Create a vertical garden on any wall using old PET bottles. Perfect for herbs and small flowers.',
    author: 'Rahul M.', authorColor: '#d4745a', trending: false,
    materials: ['2L plastic bottles', 'Craft knife', 'Rope/wire', 'Soil', 'Seeds or seedlings'],
    steps: [
      { title: 'Cut the Opening', desc: 'Cut a rectangular opening (about 15×8cm) on one side of the bottle, 5cm from the top.' },
      { title: 'Punch Drainage Holes', desc: 'Use a heated nail to poke 4-5 small holes in the bottom of the bottle for drainage.' },
      { title: 'Create Hanging Loop', desc: 'Pierce two holes near the bottle cap. Thread rope through and tie securely to hang.' },
      { title: 'Fill with Soil', desc: 'Add gravel at the bottom, then fill with potting soil to within 2cm of the opening.' },
      { title: 'Plant & Hang', desc: 'Plant your seeds or seedlings, water gently, and hang on any sunny wall or fence.' }
    ]
  },
  {
    id: 3, emoji: '💡', bg: 'linear-gradient(135deg,#5a4a2d,#8c7a50)',
    title: 'Jar into Ambient Lamp', category: 'Home', difficulty: 'medium',
    time: '1 hr', likes: 347, saved: 142, comments: 61,
    desc: 'Turn any old glass jar into a beautiful ambient lamp with fairy lights or candle.',
    author: 'Sneha K.', authorColor: '#7ab88a', trending: true,
    materials: ['Glass jar', 'Fairy lights (battery powered)', 'Twine or jute', 'Pebbles or marbles', 'Hot glue gun'],
    steps: [
      { title: 'Clean the Jar', desc: 'Remove all labels and thoroughly clean the jar. Let it dry completely.' },
      { title: 'Add Decorative Base', desc: 'Place a layer of pebbles, marbles, or sand at the bottom of the jar for a beautiful visual base.' },
      { title: 'Arrange Fairy Lights', desc: 'Loosely coil the fairy lights inside the jar, leaving the battery pack outside.' },
      { title: 'Decorate the Exterior', desc: 'Wrap twine around the neck of the jar, securing with hot glue. Add dried flowers or leaves.' },
      { title: 'Final Touch', desc: 'Switch on the fairy lights and place in any corner, shelf, or bedside table for magical ambiance.' }
    ]
  },
  {
    id: 4, emoji: '📱', bg: 'linear-gradient(135deg,#3a3a5c,#5a5a8c)',
    title: 'Phone Box to Desk Organizer', category: 'Electronics', difficulty: 'easy',
    time: '20 min', likes: 118, saved: 44, comments: 19,
    desc: 'That old phone box gathering dust can become a chic desk organizer for pens, cables, and cards.',
    author: 'Aditya P.', authorColor: '#d4a843', trending: false,
    materials: ['Old phone box', 'Craft paper', 'Glue', 'Scissors', 'Paint or washi tape'],
    steps: [
      { title: 'Decide the Layout', desc: 'Decide what you want to store — pens, cards, cables. Sketch a simple layout.' },
      { title: 'Cut Dividers', desc: 'Cut strips from the inner box tray to create compartments that fit your needs.' },
      { title: 'Cover with Paper', desc: 'Cut decorative craft paper and glue it over the box exterior for a fresh look.' },
      { title: 'Label Compartments', desc: 'Use a marker or sticker labels to mark each compartment.' }
    ]
  },
  {
    id: 5, emoji: '📰', bg: 'linear-gradient(135deg,#5a4a2d,#7a6a4a)',
    title: 'Newspaper Magazine Holder', category: 'Paper', difficulty: 'easy',
    time: '25 min', likes: 89, saved: 31, comments: 14,
    desc: 'Roll old newspapers into sturdy rods and weave them into a stylish magazine or remote holder.',
    author: 'Meera J.', authorColor: '#4a8c5c', trending: false,
    materials: ['Old newspapers', 'Glue', 'Cardboard base', 'Varnish', 'Paint'],
    steps: [
      { title: 'Roll the Rods', desc: 'Roll newspaper sheets diagonally into tight rods. Secure the end with a dab of glue.' },
      { title: 'Create the Base', desc: 'Cut a cardboard rectangle for the base. Glue rods upright around the perimeter.' },
      { title: 'Weave the Sides', desc: 'Weave horizontal rods through the vertical ones in an over-under pattern to form the walls.' },
      { title: 'Seal & Paint', desc: 'Apply a coat of white glue (diluted) or varnish to strengthen. Paint in any color when dry.' }
    ]
  },
  {
    id: 6, emoji: '🌿', bg: 'linear-gradient(135deg,#2d5a3d,#4a8c5c)',
    title: 'Tin Can Herb Garden', category: 'Garden', difficulty: 'easy',
    time: '15 min', likes: 221, saved: 88, comments: 35,
    desc: 'Repurpose tin cans from your kitchen into a beautiful windowsill herb garden for fresh cooking herbs.',
    author: 'Kavya R.', authorColor: '#c4956a', trending: true,
    materials: ['Empty tin cans', 'Hammer & nail', 'Paint', 'Soil', 'Herb seeds/seedlings'],
    steps: [
      { title: 'Clean the Cans', desc: 'Remove labels, wash thoroughly, and smooth any sharp edges with sandpaper.' },
      { title: 'Add Drainage', desc: 'Use hammer and nail to punch 5-6 drainage holes in the bottom of each can.' },
      { title: 'Paint & Decorate', desc: 'Apply a coat of spray paint or acrylic paint. Add patterns, labels, or leave natural.' },
      { title: 'Plant Your Herbs', desc: 'Fill with potting mix, plant herb seeds or seedlings, and water lightly.' },
      { title: 'Display Creatively', desc: 'Group cans together on a windowsill, mount on a wooden plank, or place on a shelf.' }
    ]
  }
];

const aiSuggestions = {
  'plastic bottle': ['🌱 Vertical planter', '🕯️ Candle holder', '🐦 Bird feeder', '✏️ Pencil organizer', '💧 Self-watering pot'],
  'jeans': ['👜 Tote bag', '📱 Phone pouch', '🧸 Denim bear toy', '🛋️ Cushion cover', '💼 Laptop sleeve'],
  't-shirt': ['🛍️ Shopping bag', '🧶 Braided rug', '🎀 Headband', '🌿 Plant tie', '🎨 Canvas for painting'],
  'newspaper': ['🗃️ Magazine holder', '📦 Gift wrap', '🗺️ Paper mache bowl', '🏺 Vase decoration', '📚 Book cover'],
  'tin can': ['🌿 Herb garden', '💡 Lamp shade', '🖊️ Desk organizer', '🕯️ Lantern', '🎵 Wind chime'],
  'cardboard': ['📦 Desk organizer', '🎮 Toy castle', '🌱 Seedling trays', '🎨 Art canvas', '🗂️ File holder'],
  'default': ['🌱 Planter or vase', '💡 Lamp or lantern', '🛍️ Storage container', '🎨 Art project', '🐦 Bird feeder']
};

const comments = [
  { name: 'Ananya', text: 'This is genius! Made this last weekend and it turned out even better than expected 🌿', time: '2 hours ago', color: '#4a8c5c' },
  { name: 'Dev', text: 'Great tutorial. One tip: use denim with a tight weave for a stronger bag.', time: '5 hours ago', color: '#7ab88a' },
  { name: 'Prachi', text: 'I used this for a school project on sustainability. Got an A+ 🎉', time: '1 day ago', color: '#d4a843' }
];

// ===== RENDER CARDS =====
let currentFilter = 'All';
let likedCards = new Set();
let savedCards = new Set();

function renderCards(data) {
  const grid = document.getElementById('cardsGrid');
  document.getElementById('resultCount').textContent = data.length;
  grid.innerHTML = data.map(idea => `
    <div class="idea-card" onclick="openModal(${idea.id})" data-category="${idea.category}" data-title="${idea.title.toLowerCase()}">
      ${idea.trending ? '<div class="trending-badge"></div>' : ''}
      <div class="card-media">
        <div class="card-media-inner" style="background:${idea.bg}; font-size:4rem;">${idea.emoji}</div>
        <div class="card-badge-cat">${idea.category}</div>
        <div class="card-badge-diff diff-${idea.difficulty}">${idea.difficulty}</div>
        <div class="card-play-btn"><div class="play-circle">▶</div></div>
      </div>
      <div class="card-body">
        <div class="card-title">${idea.title}</div>
        <div class="card-meta">
          <div class="card-meta-item">⏱ ${idea.time}</div>
          <div class="card-meta-item">💬 ${idea.comments}</div>
          <div class="card-meta-item">${idea.difficulty === 'easy' ? '😊' : idea.difficulty === 'medium' ? '🔧' : '💪'} ${idea.difficulty}</div>
        </div>
        <div class="card-desc">${idea.desc}</div>
        <div class="card-footer">
          <div class="card-author">
            <div class="author-avatar" style="background:${idea.authorColor}">${idea.author[0]}</div>
            <div class="author-name">${idea.author}</div>
          </div>
          <div class="card-actions" onclick="event.stopPropagation()">
            <button class="card-action-btn ${likedCards.has(idea.id)?'liked':''}" onclick="toggleLike(${idea.id}, this)">
              ❤️ <span>${idea.likes + (likedCards.has(idea.id)?1:0)}</span>
            </button>
            <button class="card-action-btn ${savedCards.has(idea.id)?'saved':''}" onclick="toggleSave(${idea.id}, this)">
              ${savedCards.has(idea.id)?'🔖':'🔖'} <span>${savedCards.has(idea.id)?'Saved':'Save'}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  `).join('');
}

function filterCards() {
  const search = document.getElementById('searchInput').value.toLowerCase();
  const filtered = ideas.filter(i => {
    const matchCat = currentFilter === 'All' || i.category === currentFilter;
    const matchSearch = !search || i.title.toLowerCase().includes(search) || i.desc.toLowerCase().includes(search);
    return matchCat && matchSearch;
  });
  renderCards(filtered);
}

function setFilter(cat, el) {
  currentFilter = cat;
  document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  filterCards();
}

function setSortActive(el) {
  document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
}

function toggleLike(id, btn) {
  if (likedCards.has(id)) { likedCards.delete(id); btn.classList.remove('liked'); }
  else { likedCards.add(id); btn.classList.add('liked'); showToast('❤️ Liked!'); }
  filterCards();
}
function toggleSave(id, btn) {
  if (savedCards.has(id)) { savedCards.delete(id); btn.classList.remove('saved'); }
  else { savedCards.add(id); btn.classList.add('saved'); showToast('🔖 Saved to your collection!'); }
  filterCards();
}

// ===== MODAL =====
function openModal(id) {
  const idea = ideas.find(i => i.id === id);
  if (!idea) return;

  document.getElementById('modalHero').style.background = idea.bg;
  document.getElementById('modalHero').innerHTML = `
    <button class="modal-close" onclick="closeModalDirect()">✕</button>
    <div style="font-size:5rem;">${idea.emoji}</div>
  `;

  document.getElementById('modalContent').innerHTML = `
    <div class="modal-title">${idea.title}</div>
    <div class="modal-meta">
      <span class="modal-badge cat">📁 ${idea.category}</span>
      <span class="modal-badge time">⏱ ${idea.time}</span>
      <span class="modal-badge diff">${idea.difficulty === 'easy' ? '😊' : idea.difficulty === 'medium' ? '🔧' : '💪'} ${idea.difficulty}</span>
    </div>
    <div class="modal-desc">${idea.desc}</div>
    <div class="materials-section">
      <div class="materials-title">🛠 Materials Needed</div>
      <div class="materials-list">${idea.materials.map(m => `<span class="material-tag">${m}</span>`).join('')}</div>
    </div>
    <div class="steps-header">📋 Step-by-Step Instructions</div>
    ${idea.steps.map((s, i) => `
      <div class="step-item">
        <div class="step-num">${i+1}</div>
        <div class="step-content-wrap">
          <div class="step-title">${s.title}</div>
          <div class="step-desc">${s.desc}</div>
          ${s.tip ? `<div class="step-tip">💡 Pro tip: ${s.tip}</div>` : ''}
        </div>
      </div>
    `).join('')}
  `;

  renderComments();
  document.getElementById('modalOverlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function renderComments() {
  document.getElementById('commentsList').innerHTML = comments.map(c => `
    <div class="comment">
      <div class="comment-avatar" style="background:${c.color}">${c.name[0]}</div>
      <div class="comment-bubble">
        <div class="comment-author">${c.name}</div>
        <div class="comment-text">${c.text}</div>
        <div class="comment-time">${c.time}</div>
      </div>
    </div>
  `).join('');
}

function submitComment() {
  const input = document.getElementById('commentInput');
  if (!input.value.trim()) return;
  comments.unshift({ name: 'You', text: input.value, time: 'Just now', color: '#2d5a3d' });
  input.value = '';
  renderComments();
  showToast('💬 Comment posted!');
}

function closeModal(e) { if (e.target === document.getElementById('modalOverlay')) closeModalDirect(); }
function closeModalDirect() {
  document.getElementById('modalOverlay').classList.remove('open');
  document.body.style.overflow = '';
}

// ===== BEFORE/AFTER SLIDER =====
let baActive = false;
let baContainer = null;

function baStart(e) {
  baActive = true;
  baContainer = document.getElementById('baContainer');
  document.addEventListener('mousemove', baMove);
  document.addEventListener('mouseup', baEnd);
  baMove(e);
}
function baTouchStart(e) {
  baActive = true;
  baContainer = document.getElementById('baContainer');
  document.addEventListener('touchmove', baTouchMove);
  document.addEventListener('touchend', baEnd);
  baTouchMove(e);
}
function baMove(e) {
  if (!baActive) return;
  const rect = baContainer.getBoundingClientRect();
  let x = ((e.clientX - rect.left) / rect.width) * 100;
  x = Math.min(Math.max(x, 2), 98);
  document.getElementById('baDivider').style.left = x + '%';
  document.getElementById('baClip').style.clipPath = `inset(0 ${100-x}% 0 0)`;
}
function baTouchMove(e) {
  baMove(e.touches[0]);
}
function baEnd() {
  baActive = false;
  document.removeEventListener('mousemove', baMove);
  document.removeEventListener('mouseup', baEnd);
  document.removeEventListener('touchmove', baTouchMove);
  document.removeEventListener('touchend', baEnd);
}

// Initialize B/A
document.getElementById('baDivider').style.left = '50%';
document.getElementById('baClip').style.clipPath = 'inset(0 50% 0 0)';

// ===== AI SUGGEST =====
function generateAiIdeas() {
  const input = document.getElementById('aiTextInput').value.toLowerCase().trim();
  if (!input && !document.getElementById('aiDrop').dataset.file) {
    showToast('💡 Type what item you have!');
    return;
  }
  document.getElementById('aiResults').style.display = 'none';
  document.getElementById('aiLoading').style.display = 'block';

  setTimeout(() => {
    document.getElementById('aiLoading').style.display = 'none';
    let suggestions = aiSuggestions.default;
    for (const key in aiSuggestions) {
      if (input.includes(key)) { suggestions = aiSuggestions[key]; break; }
    }
    document.getElementById('aiChips').innerHTML = suggestions.map(s =>
      `<div class="ai-chip" onclick="showToast('🔍 Finding '${s}' tutorials...')">${s}</div>`
    ).join('');
    document.getElementById('aiResults').style.display = 'block';
  }, 1400);
}
function handleAiFile(e) {
  if (e.target.files[0]) {
    document.getElementById('aiDrop').innerHTML = `<div class="ai-drop-icon">✅</div><div class="ai-drop-text"><strong>${e.target.files[0].name}</strong> uploaded! Click Generate.</div>`;
    document.getElementById('aiDrop').dataset.file = '1';
  }
}
function aiDragOver(e) { e.preventDefault(); document.getElementById('aiDrop').classList.add('drag-over'); }
function aiDrop(e) { e.preventDefault(); document.getElementById('aiDrop').classList.remove('drag-over'); }

// ===== UPLOAD TABS =====
function switchTab(tab, el) {
  document.querySelectorAll('.upload-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.upload-tab-content').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('tab-' + tab).classList.add('active');
}

// ===== STEPS BUILDER =====
function addStep() {
  const builder = document.getElementById('stepsBuilder');
  const count = builder.children.length + 1;
  const div = document.createElement('div');
  div.className = 'step-builder-item';
  div.innerHTML = `
    <div class="step-num-badge">${count}</div>
    <input class="form-input" type="text" placeholder="Describe step ${count}..." style="flex:1;">
    <button class="remove-step-btn" onclick="removeStep(this)">✕</button>
  `;
  builder.appendChild(div);
}
function removeStep(btn) {
  const item = btn.closest('.step-builder-item');
  if (document.getElementById('stepsBuilder').children.length > 1) {
    item.remove();
    renumberSteps();
  }
}
function renumberSteps() {
  document.querySelectorAll('#stepsBuilder .step-num-badge').forEach((n, i) => n.textContent = i+1);
  document.querySelectorAll('#stepsBuilder .form-input').forEach((inp, i) => inp.placeholder = `Describe step ${i+1}...`);
}

// ===== DIFFICULTY SELECTOR =====
function selectDiff(btn, level) {
  const siblings = btn.parentElement.querySelectorAll('.diff-opt');
  siblings.forEach(s => s.classList.remove('active'));
  btn.classList.add('active');
}

// ===== TOAST =====
function showToast(msg) {
  const toast = document.getElementById('toast');
  document.getElementById('toastMsg').textContent = msg;
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 2800);
}

// ===== SCROLL REVEAL =====
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// ===== CHALLENGE TIMER =====
function updateTimer() {
  const now = new Date();
  const target = new Date(now);
  target.setDate(target.getDate() + ((7 - now.getDay()) % 7 || 7));
  target.setHours(23, 59, 59, 0);
  const diff = target - now;
  const d = Math.floor(diff / 86400000);
  const h = Math.floor((diff % 86400000) / 3600000);
  const m = Math.floor((diff % 3600000) / 60000);
  document.getElementById('timer-d').textContent = String(d).padStart(2, '0');
  document.getElementById('timer-h').textContent = String(h).padStart(2, '0');
  document.getElementById('timer-m').textContent = String(m).padStart(2, '0');
}
updateTimer();
setInterval(updateTimer, 60000);

// ===== INIT =====
renderCards(ideas);