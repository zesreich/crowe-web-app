/* HSY Atlas — interactive ecosystem mockup (no backend) */

const storedTheme = localStorage.getItem("hsy-atlas-theme");
const state = {
  view: "overview",
  selectedFolder: "demir-banka",
  pending: 7,
  routed: 24,
  theme:
    storedTheme ||
    (window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"),
};

const FIRMS = [
  { id: "demir", name: "Demir Holding A.Ş.", period: "2025", group: "a", status: "ok", lead: "Eda" },
  { id: "nirengi", name: "Nirengi Tekstil", period: "2025", group: "a", status: "waiting", lead: "İrem" },
  { id: "kuzey", name: "Kuzey Lojistik", period: "2025", group: "b", status: "syncing", lead: "Hakan" },
  { id: "ada", name: "Ada Gıda Sanayi", period: "2025", group: "b", status: "ok", lead: "Özkan" },
  { id: "mavi", name: "Mavi Enerji", period: "2025", group: "c", status: "conflict", lead: "Mert" },
  { id: "oren", name: "Ören İnşaat", period: "2024", group: "c", status: "waiting", lead: "Hakan" },
];

const GROUPS = [
  {
    id: "a",
    name: "Grup A",
    manager: "Eda Sefer",
    seniors: ["İrem Gülmez"],
    members: ["Ayşe K.", "Can B."],
  },
  {
    id: "b",
    name: "Grup B",
    manager: "Özkan Cengiz",
    seniors: ["Hakan Kılıç"],
    members: ["Deniz Y.", "Selin T."],
  },
  {
    id: "c",
    name: "Grup C",
    manager: "Mehmet Ali Sarıad",
    seniors: ["Mert Cengiz"],
    members: ["Burak A.", "Zeynep E."],
  },
];

const TREE = [
  {
    id: "demir",
    name: "Demir Holding A.Ş.",
    kind: "firm",
    state: "ok",
    children: [
      {
        id: "demir-banka",
        name: "B10 · Banka mutabakatları",
        kind: "folder",
        state: "ok",
        path: "/Firmalar/Demir Holding/B10 Banka",
        files: [
          { name: "Garanti_Mutabakat_2025.xlsx", status: "ok", by: "Nirengi portal", size: "184 KB" },
          { name: "İşBankası_Ekstre.pdf", status: "ok", by: "müşteri", size: "2.1 MB" },
          { name: "Mutabakat_özet.docx", status: "ok", by: "İrem G.", size: "96 KB" },
        ],
      },
      {
        id: "demir-stok",
        name: "C30 · Stok sayım",
        kind: "folder",
        state: "waiting",
        path: "/Firmalar/Demir Holding/C30 Stok",
        files: [
          { name: "Depo_sayım_taslak.xlsx", status: "waiting", by: "talep açık", size: "—" },
        ],
      },
    ],
  },
  {
    id: "kuzey",
    name: "Kuzey Lojistik",
    kind: "firm",
    state: "syncing",
    children: [
      {
        id: "kuzey-hukuk",
        name: "H01 · Hukuki teyit",
        kind: "folder",
        state: "syncing",
        path: "/Firmalar/Kuzey Lojistik/H01 Hukuk",
        files: [
          { name: "Avukat_teyit_v2.docx", status: "syncing", by: "Mert C.", size: "412 KB" },
          { name: "Dava_listesi.xlsx", status: "ok", by: "müşteri", size: "78 KB" },
        ],
      },
    ],
  },
  {
    id: "mavi",
    name: "Mavi Enerji",
    kind: "firm",
    state: "conflict",
    children: [
      {
        id: "mavi-sabit",
        name: "D12 · Sabit kıymet",
        kind: "folder",
        state: "conflict",
        path: "/Firmalar/Mavi Enerji/D12 Sabit",
        files: [
          { name: "SK_listesi.xlsx", status: "conflict", by: "2 sürüm", size: "640 KB" },
        ],
      },
    ],
  },
];

const TEMPLATE_PATHS = {
  banka: "B10 · Banka mutabakatları",
  stok: "C30 · Stok sayım tutanakları",
  sabit: "D12 · Sabit kıymet listesi",
  hukuk: "H01 · Hukuki teyit mektubu",
};

const LIVE = [
  { t: "09:14", text: "Demir Holding · banka ekstresi otomatik yönlendirildi." },
  { t: "09:02", text: "Grup B · Ada Gıda ataması güncellendi." },
  { t: "08:47", text: "Kuzey Lojistik klasörü Mac’te senkronlandı." },
  { t: "08:21", text: "Nirengi Tekstil · stok sayım talebi gönderildi." },
];

const INTAKE = [
  {
    file: "Garanti_Mutabakat_2025.xlsx",
    firm: "Demir Holding A.Ş.",
    target: "/Firmalar/Demir Holding/B10 Banka",
    when: "09:14",
  },
  {
    file: "Dava_listesi.xlsx",
    firm: "Kuzey Lojistik",
    target: "/Firmalar/Kuzey Lojistik/H01 Hukuk",
    when: "dün",
  },
];

const $ = (sel, root = document) => root.querySelector(sel);
const $$ = (sel, root = document) => [...root.querySelectorAll(sel)];

function applyTheme() {
  document.documentElement.setAttribute("data-theme", state.theme);
  localStorage.setItem("hsy-atlas-theme", state.theme);
  const icon = $("#themeIcon");
  if (icon) {
    icon.className = state.theme === "dark" ? "ph ph-sun" : "ph ph-moon";
  }
}

function toast(title, detail) {
  const el = document.createElement("div");
  el.className = "toast";
  el.dataset.kind = "success";
  el.innerHTML = `
    <i class="ph ph-check-circle toast-ico" aria-hidden="true"></i>
    <div>
      <strong>${title}</strong>
      <span>${detail}</span>
    </div>`;
  $("#toasts").appendChild(el);
  setTimeout(() => {
    el.style.opacity = "0";
    el.style.transform = "translateY(6px)";
    el.style.transition = "opacity 160ms var(--ease), transform 160ms var(--ease)";
    setTimeout(() => el.remove(), 200);
  }, 3200);
}

function setView(view) {
  state.view = view;
  $$(".nav-btn").forEach((btn) => {
    btn.setAttribute("aria-current", btn.dataset.view === view ? "page" : "false");
  });
  $$(".view").forEach((section) => {
    const active = section.id === `view-${view}`;
    section.classList.toggle("is-active", active);
    section.hidden = !active;
  });
  const titles = {
    overview: "Genel bakış",
    files: "Dosya ağacı",
    groups: "Grup & firma",
    intake: "Müşteri yükleme",
  };
  $("#crumb").innerHTML = `<span>Atlas</span><i class="ph ph-caret-right" aria-hidden="true"></i><strong>${titles[view]}</strong>`;
}

function statusBadge(status) {
  const map = {
    ok: ['badge-ok', 'Senkron'],
    waiting: ['badge-wait', 'Bekliyor'],
    syncing: ['badge-ok', 'Senkronlanıyor'],
    conflict: ['badge-danger', 'Çakışma'],
  };
  const [cls, label] = map[status] || ['', status];
  return `<span class="badge ${cls}">${label}</span>`;
}

function renderOverview() {
  const hot = $("#hotFirms");
  hot.innerHTML = FIRMS.map(
    (f) => `
    <li>
      <article class="firm-card" data-goto-firm="${f.id}">
        <h4>${f.name}</h4>
        <p>Grup ${f.group.toUpperCase()} · ${f.lead} · ${statusBadge(f.status)}</p>
      </article>
    </li>`
  ).join("");

  $("#liveFeed").innerHTML = LIVE.map(
    (row) => `<li><time>${row.t}</time><div>${row.text}</div></li>`
  ).join("");

  $("#statPending").textContent = String(state.pending);
  $("#statRouted").textContent = String(state.routed);
}

function findFolder(id, nodes = TREE) {
  for (const n of nodes) {
    if (n.id === id) return n;
    if (n.children) {
      const hit = findFolder(id, n.children);
      if (hit) return hit;
    }
  }
  return null;
}

function renderTree(nodes = TREE, depth = 0) {
  return nodes
    .map((node) => {
      const hasKids = node.children && node.children.length;
      const selected = node.id === state.selectedFolder;
      const row = `
        <li role="treeitem" aria-expanded="${hasKids ? "true" : undefined}">
          <button type="button" class="tree-row ${selected ? "is-selected" : ""}"
            data-folder="${node.id}" style="padding-left: ${0.45 + depth * 0.15}rem">
            ${hasKids ? '<i class="ph ph-caret-right chev" aria-hidden="true"></i>' : '<span style="width:0.9rem"></span>'}
            <span class="status-dot" data-state="${node.state}"></span>
            <i class="ph ${node.kind === "firm" ? "ph-buildings" : "ph-folder"}" aria-hidden="true"></i>
            <span class="name">${node.name}</span>
          </button>
          ${hasKids ? `<ul role="group">${renderTree(node.children, depth + 1)}</ul>` : ""}
        </li>`;
      return row;
    })
    .join("");
}

function renderFiles() {
  $("#fileTree").innerHTML = renderTree();
  const folder = findFolder(state.selectedFolder) || findFolder("demir-banka");
  $("#fileDetailTitle").textContent = folder?.name || "Dosyalar";
  $("#fileDetailPath").textContent = folder?.path || "—";
  $("#treeCount").textContent = `${FIRMS.length} firma`;

  const rows = folder?.files || [];
  $("#fileRows").innerHTML = rows
    .map(
      (f) => `
    <tr>
      <td><div class="file-name"><i class="ph ph-file"></i>${f.name}</div></td>
      <td>${statusBadge(f.status === "syncing" ? "syncing" : f.status)}</td>
      <td class="mono">${f.by}</td>
      <td class="mono">${f.size}</td>
    </tr>`
    )
    .join("");
}

function renderGroups() {
  const board = $("#groupBoard");
  board.innerHTML = GROUPS.map((g) => {
    const firms = FIRMS.filter((f) => f.group === g.id);
    return `
      <section class="group-col" data-group="${g.id}">
        <div class="group-col-head">
          <h3>${g.name}</h3>
          <div class="team">
            <span class="chip"><strong>Müdür</strong> ${g.manager}</span>
            ${g.seniors.map((s) => `<span class="chip"><strong>Kıdemli</strong> ${s}</span>`).join("")}
            ${g.members.map((m) => `<span class="chip">${m}</span>`).join("")}
          </div>
        </div>
        <ul class="firm-list" data-drop="${g.id}">
          ${firms
            .map(
              (f) => `
            <li>
              <article class="firm-card" draggable="true" data-firm="${f.id}">
                <h4>${f.name}</h4>
                <p>${f.period} · ${statusBadge(f.status)}</p>
              </article>
            </li>`
            )
            .join("")}
        </ul>
      </section>`;
  }).join("");

  // drag & drop
  $$(".firm-card[draggable]", board).forEach((card) => {
    card.addEventListener("dragstart", (e) => {
      card.classList.add("is-dragging");
      e.dataTransfer.setData("text/firm", card.dataset.firm);
      e.dataTransfer.effectAllowed = "move";
    });
    card.addEventListener("dragend", () => card.classList.remove("is-dragging"));
  });

  $$(".group-col", board).forEach((col) => {
    col.addEventListener("dragover", (e) => {
      e.preventDefault();
      col.classList.add("is-over");
    });
    col.addEventListener("dragleave", () => col.classList.remove("is-over"));
    col.addEventListener("drop", (e) => {
      e.preventDefault();
      col.classList.remove("is-over");
      const firmId = e.dataTransfer.getData("text/firm");
      const firm = FIRMS.find((f) => f.id === firmId);
      if (!firm) return;
      const target = col.dataset.group;
      if (firm.group === target) return;
      firm.group = target;
      toast("Firma atandı", `${firm.name} → Grup ${target.toUpperCase()}`);
      LIVE.unshift({
        t: nowHM(),
        text: `${firm.name} · Grup ${target.toUpperCase()}’ye taşındı.`,
      });
      renderGroups();
      renderOverview();
    });
  });
}

function firmName(id) {
  return FIRMS.find((f) => f.id === id)?.name || id;
}

function updatePathPreview() {
  const firmId = $("#reqFirm").value;
  const tpl = $("#reqTemplate").value;
  const folder = TEMPLATE_PATHS[tpl];
  const name = firmName(firmId);
  $("#pathPreview").innerHTML = `Hedef: <strong>/Firmalar/${name}/${folder}</strong>`;
}

function renderIntake() {
  const sel = $("#reqFirm");
  sel.innerHTML = FIRMS.map((f) => `<option value="${f.id}">${f.name}</option>`).join("");
  updatePathPreview();

  $("#intakeFeed").innerHTML = INTAKE.map(
    (item) => `
    <li class="feed-item">
      <div class="feed-ico"><i class="ph ph-file-arrow-down"></i></div>
      <div class="feed-body">
        <strong>${item.file}</strong>
        <span>${item.firm} → <span class="mono">${item.target}</span></span>
      </div>
      <span class="mono">${item.when}</span>
    </li>`
  ).join("");
  $("#inboxBadge").textContent = `${INTAKE.filter((i) => i.when !== "dün").length || INTAKE.length} kayıt`;
}

function nowHM() {
  const d = new Date();
  return `${String(d.getHours()).padStart(2, "0")}:${String(d.getMinutes()).padStart(2, "0")}`;
}

function simulateSync() {
  const pill = $("#syncPill");
  const label = $("#syncLabel");
  pill.dataset.state = "syncing";
  label.textContent = "Senkronlanıyor…";
  toast("Senkron başladı", "3 cihaz üzerinde dosya listesi karşılaştırılıyor.");
  setTimeout(() => {
    pill.dataset.state = "idle";
    label.textContent = "3 cihaz · güncel";
    toast("Senkron tamam", "Tüm klasörler ofis genelinde hizalandı.");
    LIVE.unshift({ t: nowHM(), text: "Ofis senkronu tamamlandı · 3 cihaz güncel." });
    renderOverview();
  }, 1600);
}

function simulateClientUpload() {
  const firmId = $("#reqFirm").value;
  const tpl = $("#reqTemplate").value;
  const firm = FIRMS.find((f) => f.id === firmId);
  const folder = TEMPLATE_PATHS[tpl];
  const fileName = `${folder.split("·")[0].trim()}_musteri_${Date.now().toString().slice(-4)}.pdf`;
  const target = `/Firmalar/${firm.name}/${folder}`;

  INTAKE.unshift({
    file: fileName,
    firm: firm.name,
    target,
    when: nowHM(),
  });
  state.routed += 1;
  state.pending = Math.max(0, state.pending - 1);
  $("#statRouted").textContent = String(state.routed);
  $("#statPending").textContent = String(state.pending);

  // inject into tree files if matching banka on demir
  const demirBanka = findFolder("demir-banka");
  if (demirBanka && firmId === "demir" && tpl === "banka") {
    demirBanka.files.unshift({
      name: fileName,
      status: "ok",
      by: "müşteri · otomatik",
      size: "1.4 MB",
    });
  }

  renderIntake();
  renderFiles();
  LIVE.unshift({
    t: nowHM(),
    text: `${firm.name} · ${fileName} otomatik yönlendirildi.`,
  });
  renderOverview();

  const first = $("#intakeFeed .feed-item");
  if (first) first.classList.add("is-new");

  toast("Yükleme yönlendirildi", `${fileName} → ${folder}`);

  // flash in files table if visible
  setTimeout(() => {
    const row = $("#fileRows tr");
    if (row && firmId === "demir" && tpl === "banka") row.classList.add("flash-route");
  }, 50);
}

function bind() {
  $$(".nav-btn").forEach((btn) => {
    btn.addEventListener("click", () => setView(btn.dataset.view));
  });

  document.addEventListener("click", (e) => {
    const goto = e.target.closest("[data-goto]");
    if (goto) {
      setView(goto.dataset.goto);
      return;
    }
    const firmCard = e.target.closest("[data-goto-firm]");
    if (firmCard) {
      state.selectedFolder =
        firmCard.dataset.gotoFirm === "kuzey"
          ? "kuzey-hukuk"
          : firmCard.dataset.gotoFirm === "mavi"
            ? "mavi-sabit"
            : "demir-banka";
      setView("files");
      renderFiles();
      return;
    }
    const folderBtn = e.target.closest("[data-folder]");
    if (folderBtn) {
      state.selectedFolder = folderBtn.dataset.folder;
      renderFiles();
    }
  });

  $("#themeToggle").addEventListener("click", () => {
    state.theme = state.theme === "dark" ? "light" : "dark";
    applyTheme();
  });

  $("#btnSimulateSync").addEventListener("click", simulateSync);
  $("#btnSimulateUpload").addEventListener("click", simulateClientUpload);

  $("#btnBalance").addEventListener("click", () => {
    // simple round-robin mock
    FIRMS.forEach((f, i) => {
      f.group = ["a", "b", "c"][i % 3];
    });
    toast("Dağılım dengelendi", "Firmalar gruplar arasında eşitlendi.");
    renderGroups();
    renderOverview();
  });

  $("#reqFirm").addEventListener("change", updatePathPreview);
  $("#reqTemplate").addEventListener("change", updatePathPreview);

  $("#requestForm").addEventListener("submit", (e) => {
    e.preventDefault();
    const firm = firmName($("#reqFirm").value);
    const folder = TEMPLATE_PATHS[$("#reqTemplate").value];
    state.pending += 1;
    $("#statPending").textContent = String(state.pending);
    LIVE.unshift({
      t: nowHM(),
      text: `${firm} · “${folder}” talebi gönderildi.`,
    });
    toast("Talep gönderildi", `${firm} müşteri portalına iletildi.`);
    renderOverview();
  });

  $("#btnClearForm").addEventListener("click", () => {
    $("#reqNote").value = "";
  });
}

function init() {
  applyTheme();
  bind();
  renderOverview();
  renderFiles();
  renderGroups();
  renderIntake();
  setView("overview");
}

init();
