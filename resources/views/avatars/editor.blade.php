@extends('layouts.app')

@section('title', 'Avatar Editor — Neo v3D')

@section('content')
<style>
  :root{
    --bg1:#0b1020; --bg2:#0a122e;
    --glass: rgba(255,255,255,.08); --glass-border: rgba(255,255,255,.15);
    --neon1:#7c3aed; --neon2:#06b6d4; --neon3:#22c55e;
  }
  body{ background: radial-gradient(1000px 600px at 10% -10%, #10183a 0%, #0b0f1c 60%, #0b0f1c 100%) fixed; }

  /* ===== HERO (Preview Top) ===== */
  .hero{
    position:relative; width:100%;
    height:clamp(420px, 68vh, 780px);
    border-radius: 18px; overflow:hidden;
    background: linear-gradient(120deg, rgba(124,58,237,.08), rgba(6,182,212,.08));
    box-shadow: 0 30px 80px rgba(0,0,0,.35), inset 0 0 0 1px rgba(255,255,255,.06);
  }
  .stage{ position:absolute; inset:0; }
  .stage canvas{ width:100%; height:100%; display:block; }

  .gridfx{ position:absolute; inset:0; pointer-events:none;
    background:
      radial-gradient(600px 280px at 10% -10%, rgba(124,58,237,.20), transparent 60%),
      radial-gradient(700px 320px at 90% -10%, rgba(6,182,212,.18), transparent 60%);
  }
  .gridfx::after{ content:""; position:absolute; inset:0;
    background-image:
      linear-gradient(rgba(255,255,255,.06) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,.06) 1px, transparent 1px);
    background-size: 36px 36px;
    mask-image: linear-gradient(to bottom, black 30%, transparent 100%);
  }
  .neon-frame{ position:absolute; inset:-2px; border-radius:20px; pointer-events:none;
    background: linear-gradient(90deg, var(--neon1), var(--neon2), var(--neon3), var(--neon1));
    background-size: 300% 100%; filter: blur(12px); opacity:.55; animation: flow 6s linear infinite;
  }
  @keyframes flow{ 0%{background-position:0% 50%} 100%{background-position:300% 50%} }

  /* ===== Dock (Controls on the right) ===== */
  .dock{
    position:absolute; top:16px; right:16px;
    width: min(380px, 38vw); max-height: calc(100% - 32px); overflow:auto;
    backdrop-filter: blur(10px); background: var(--glass);
    border: 1px solid var(--glass-border); border-radius: 16px;
    box-shadow: 0 20px 50px rgba(0,0,0,.35); color:#e5e7eb;
  }
  .dock h6{ letter-spacing:.02em; color:#cbd5e1; }
  .dock .section{ padding:14px 14px 10px; }
  .hr{ height:1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,.12), transparent); margin: 8px 0; }
  .btn-slim{ padding:.38rem .62rem; border-radius:10px; }
  .range{ width:100%; accent-color:#60a5fa; }
  .hint{ font-size:.85rem; color:#94a3b8; }
  .hud{ position:absolute; inset:0; pointer-events:none; }
  .hud .top-left { position:absolute; top:10px; left:12px; display:flex; gap:8px; }
  .hud .top-right{ position:absolute; top:10px; right:12px; display:flex; gap:8px; }
  .badge{ font-size:12px; padding:4px 10px; border-radius:999px; border:1px solid rgba(255,255,255,.22); background: rgba(0,0,0,.22); color:#d1d5db; }
  .badge-live{ border-color:rgba(34,197,94,.45); color:#22c55e; background: rgba(34,197,94,.12); }
  .badge-rt{ border-color:rgba(96,165,250,.45); color:#93c5fd; background: rgba(59,130,246,.12); }

  /* ===== Below hero ===== */
  .rowx{ display:flex; gap:16px; flex-wrap:wrap; margin-top:16px; }
  .cardx{ flex:1 1 360px; min-width:320px; padding:16px; border-radius:16px;
    background: rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08);
    color:#e5e7eb; box-shadow: 0 16px 40px rgba(0,0,0,.25);
  }
  .preview-img{ width:100%; max-height:260px; object-fit:contain; background:#0b1220; border-radius:12px; }

  @media (max-width: 992px){
    .dock{ position:static; width:100%; max-height:unset; margin-top:12px; }
    .hero{ height: 64vh; }
  }
</style>

<div class="container">
  <!-- ===== HERO: Preview (Top) ===== -->
  <div class="hero" id="hero">
    <div class="stage" id="stage"></div>
    <div class="gridfx"></div>
    <div class="neon-frame"></div>

    <div class="hud">
      <div class="top-left">
        <span class="badge badge-live">LIVE</span>
        <span class="badge badge-rt" id="hudFps">FPS —</span>
      </div>
      <div class="top-right">
        <span class="badge" id="hudStatus">Procedural head</span>
      </div>
    </div>

    <div class="dock">
      <div class="section">
        <h6 class="mb-2">Customize</h6>
        <div class="mb-2">
          <label class="form-label">Face Shape (round ←→ long)</label>
          <input type="range" id="faceShape" min="0" max="100" class="form-range range" value="50">
        </div>
        <div class="mb-2">
          <label class="form-label">Jaw Width</label>
          <input type="range" id="jawWidth" min="0" max="100" class="form-range range" value="50">
        </div>
        <div class="mb-2">
          <label class="form-label">Eye Size</label>
          <input type="range" id="eyeSize" min="20" max="80" class="form-range range" value="48">
        </div>
        <div class="mb-2">
          <label class="form-label">Mouth Width</label>
          <input type="range" id="mouthWidth" min="20" max="100" class="form-range range" value="60">
        </div>
        <div class="mb-2">
          <label class="form-label">Stylized ←→ Real</label>
          <input type="range" id="realism" min="0" max="100" class="form-range range" value="40">
        </div>
        <div class="hr"></div>
        <div class="d-flex gap-2 flex-wrap">
          <button class="btn btn-outline-light btn-slim" data-exp="neutral">🙂 Neutral</button>
          <button class="btn btn-outline-light btn-slim" data-exp="smile">😊 Smile</button>
          <button class="btn btn-outline-light btn-slim" data-exp="surprise">😮 Surprise</button>
          <button class="btn btn-outline-light btn-slim" data-exp="wink">😉 Wink</button>
          <button class="btn btn-outline-light btn-slim" data-exp="blink">😎 Blink</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== ASSETS & SAVE (Below) ===== -->
  <div class="rowx">
    <div class="cardx">
      <h6 class="mb-2">Selfie (Auto Init)</h6>
      <p class="hint">On-device landmark → auto set sliders. Image never leaves your browser.</p>
      <input type="file" id="selfie" accept="image/*" class="form-control mb-2" capture="user">
      <canvas id="selfieCanvas" class="d-none"></canvas>
      <img id="selfiePreview" alt="Selfie preview" class="preview-img mb-2" />
      <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-primary btn-slim" id="btnAuto">Auto init</button>
        <button class="btn btn-outline-light btn-slim" id="btnReset">Reset</button>
      </div>
    </div>

    <div class="cardx">
      <h6 class="mb-2">3D Model (optional)</h6>
      <p class="hint">Load <b>.vrm</b> or <b>.glb/.gltf</b>. If empty, the procedural head is used.</p>
      <input type="file" id="modelFile" accept=".vrm,.glb,.gltf,model/gltf-binary,model/gltf+json" class="form-control mb-2">
      <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-outline-light btn-slim" id="btnRecenter">Recenter View</button>
        <button class="btn btn-outline-light btn-slim" id="btnExport">Export PNG</button>
      </div>
    </div>

    <div class="cardx">
      <h6 class="mb-2">Save</h6>
      <p class="hint">Save current config JSON to DB (preview image optional).</p>
      <form id="saveForm" method="POST" action="{{ route('avatar.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="config" id="configField">
        <input type="file" name="preview" id="previewFile" class="form-control mb-2" accept="image/png">
        <button type="submit" class="btn btn-success btn-slim">Save</button>
      </form>
    </div>
  </div>
</div>

{{-- MediaPipe FaceMesh (for selfie landmarks) --}}
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/face_mesh.js"></script>

<!-- ========== ここが重要：importmap + シム ========== -->
<script async src="https://unpkg.com/es-module-shims@1.10.0/dist/es-module-shims.js"></script>
<script async src="https://cdn.jsdelivr.net/npm/es-module-shims@1.10.0/dist/es-module-shims.min.js" crossorigin="anonymous"></script>
<script type="importmap">
{
  "imports": {
    "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
    "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/",
    "@pixiv/three-vrm": "https://cdn.jsdelivr.net/npm/@pixiv/three-vrm@2.0.7/lib/three-vrm.module.js"
  }
}
</script>

{{-- Three.js + VRM --}}
<script type="module">
import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { RoomEnvironment } from 'three/addons/environments/RoomEnvironment.js';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { EffectComposer } from 'three/addons/postprocessing/EffectComposer.js';
import { RenderPass } from 'three/addons/postprocessing/RenderPass.js';
import { UnrealBloomPass } from 'three/addons/postprocessing/UnrealBloomPass.js';
import { VRMLoaderPlugin, VRMUtils, BlendShapePresetName } from '@pixiv/three-vrm';

const hero = document.getElementById('hero');
const elStage = document.getElementById('stage');
const hudStatus = document.getElementById('hudStatus');
const hudFps = document.getElementById('hudFps');
const configField = document.getElementById('configField');

/* ===== Sliders & State ===== */
const sliders = {
  faceShape : document.getElementById('faceShape'),
  jawWidth  : document.getElementById('jawWidth'),
  eyeSize   : document.getElementById('eyeSize'),
  mouthWidth: document.getElementById('mouthWidth'),
  realism   : document.getElementById('realism'),
};
const state = { faceShape:50, jawWidth:50, eyeSize:48, mouthWidth:60, realism:40, expr:'neutral' };

/* ===== Renderer / Scene ===== */
const renderer = new THREE.WebGLRenderer({ antialias:true, alpha:true, preserveDrawingBuffer:true });
renderer.outputColorSpace = THREE.SRGBColorSpace;
renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 1.18;
elStage.appendChild(renderer.domElement);

const scene = new THREE.Scene();
const pmrem = new THREE.PMREMGenerator(renderer);
scene.environment = pmrem.fromScene(new RoomEnvironment(renderer), 0.1).texture;
scene.background = new THREE.Color(0x0b1020);

const camera = new THREE.PerspectiveCamera(32, 1, 0.1, 100);
camera.position.set(0, 1.55, 3.1);

const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping = true; controls.dampingFactor = .05;
controls.target.set(0, 1.5, 0);

const hemi = new THREE.HemisphereLight(0xffffff, 0x223355, 0.85);
scene.add(hemi);
const dir = new THREE.DirectionalLight(0xffffff, 2.0);
dir.position.set(2.2, 4.2, 2.4); scene.add(dir);

/* ===== Ground ring ===== */
const floor = new THREE.Mesh(
  new THREE.RingGeometry(0.6, 3.2, 64),
  new THREE.MeshPhysicalMaterial({ color:0x0f172a, roughness:.95, metalness:.0, transmission:0, reflectivity:.04 })
);
floor.rotation.x = -Math.PI/2; floor.position.y = 0; scene.add(floor);

/* ===== Postprocessing (subtle bloom) ===== */
const composer = new EffectComposer(renderer);
composer.addPass(new RenderPass(scene, camera));
const bloom = new UnrealBloomPass(new THREE.Vector2(1,1), 0.25, 0.6, 0.85);
composer.addPass(bloom);

/* ===== Procedural Head (realer material) ===== */
const skin = new THREE.MeshPhysicalMaterial({
  color: 0xffd4c4, roughness: .38, metalness: .03, sheen: 1.0, sheenRoughness: .6, clearcoat: 0.35, clearcoatRoughness: .45,
});
const hairMat = new THREE.MeshPhysicalMaterial({ color: 0x1a1a1a, roughness: .85, metalness: .02, sheen: .6, sheenRoughness: .8 });
const eyeWhite = new THREE.MeshPhysicalMaterial({ color: 0xffffff, roughness: .15 });
const eyeIris  = new THREE.MeshPhysicalMaterial({ color: 0x111111, roughness: .65, metalness: .02 });

const proto = new THREE.Group(); proto.name = 'Procedural';
const head = new THREE.Mesh(new THREE.SphereGeometry(0.75, 96, 96), skin);   head.position.set(0,1.5,0);
const jaw  = new THREE.Mesh(new THREE.SphereGeometry(0.60, 80, 80), skin);   jaw.position.set(0,1.1,0); jaw.scale.set(1.2,.6,1.1);
const hair = new THREE.Mesh(new THREE.SphereGeometry(0.77, 96, 96), hairMat); hair.position.set(0,1.62,0); hair.scale.y = .64;
const eyeLw = new THREE.Mesh(new THREE.SphereGeometry(0.15, 48, 48), eyeWhite); eyeLw.position.set(-0.26,1.5,0.6);
const eyeRw = eyeLw.clone(); eyeRw.position.x *= -1;
const irisL = new THREE.Mesh(new THREE.SphereGeometry(0.07, 32, 32), eyeIris); irisL.position.set(-0.26,1.5,0.75);
const irisR = irisL.clone(); irisR.position.x *= -1;
const mouth = new THREE.Mesh(new THREE.CapsuleGeometry(0.28, 0.02, 12, 24), new THREE.MeshPhysicalMaterial({ color:0xaa3355, roughness:.5 }));
mouth.rotation.x = Math.PI/2; mouth.position.set(0,1.18,0.65);
proto.add(head,jaw,hair,eyeLw,eyeRw,irisL,irisR,mouth); scene.add(proto);

let currentVRM = null; let currentGLTF = null;

/* ===== Loaders ===== */
const loader = new GLTFLoader();
loader.register((parser)=> new VRMLoaderPlugin(parser));
function clearModel(){
  if (currentVRM){ scene.remove(currentVRM.scene); currentVRM.dispose(); currentVRM=null; }
  if (currentGLTF){ scene.remove(currentGLTF.scene || currentGLTF); currentGLTF=null; }
}

/* ===== Mapping ===== */
function applyProcedural(){
  const faceLong = THREE.MathUtils.lerp(0.92, 1.20, (state.faceShape)/100);
  head.scale.set(1, faceLong, 1);
  jaw.scale.set(THREE.MathUtils.lerp(0.9, 1.42, state.jawWidth/100), 0.6, 1.1);

  const es = THREE.MathUtils.lerp(0.12, 0.2, (state.eyeSize-20)/60);
  eyeLw.scale.setScalar(es/0.15); eyeRw.scale.setScalar(es/0.15);
  irisL.scale.setScalar(THREE.MathUtils.lerp(0.7, 1.18, (state.eyeSize-20)/60)); irisR.scale.copy(irisL.scale);

  const mw = THREE.MathUtils.lerp(0.2, 0.46, (state.mouthWidth-20)/80);
  mouth.scale.set(mw/0.28, 1, 1);

  const t = state.realism/100;
  renderer.toneMappingExposure = THREE.MathUtils.lerp(1.05, 1.28, t);
  skin.roughness = THREE.MathUtils.lerp(.42, .32, t);
  skin.clearcoat = THREE.MathUtils.lerp(.25, .45, t);
  dir.intensity = THREE.MathUtils.lerp(1.4, 2.2, t);

  if (state.expr==='smile'){ mouth.position.y = 1.19; mouth.scale.y = 1.18; }
  else if (state.expr==='surprise'){ mouth.scale.set(0.22/0.28, 2.2, 1); mouth.position.y = 1.2; }
  else if (state.expr==='wink'){ eyeLw.scale.y = 0.16; irisL.scale.y = 0.16; }
  else if (state.expr==='blink'){
    const oyL=eyeLw.scale.y, oyR=eyeRw.scale.y, piL=irisL.scale.y, piR=irisR.scale.y;
    eyeLw.scale.y=eyeRw.scale.y=0.12; irisL.scale.y=irisR.scale.y=0.12;
    setTimeout(()=>{ eyeLw.scale.y=oyL; eyeRw.scale.y=oyR; irisL.scale.y=piL; irisR.scale.y=piR; state.expr='neutral'; }, 220);
  } else { mouth.scale.set(1,1,1); mouth.position.y=1.18; }
}

function applyVRM(){
  if (!currentVRM) return;
  const headNode = currentVRM.humanoid?.getBoneNode('head');
  if (headNode){
    headNode.scale.set(
      THREE.MathUtils.lerp(0.95, 1.1, state.jawWidth/100),
      THREE.MathUtils.lerp(0.95, 1.15, state.faceShape/100),
      1
    );
  }
  const t = state.realism/100;
  renderer.toneMappingExposure = THREE.MathUtils.lerp(1.05, 1.28, t);
  dir.intensity = THREE.MathUtils.lerp(1.4, 2.0, t);

  if (currentVRM.expressionManager){
    const em = currentVRM.expressionManager;
    em.setValue(BlendShapePresetName.Joy, 0);
    em.setValue(BlendShapePresetName.A, 0);
    if (state.expr==='smile')    em.setValue(BlendShapePresetName.Joy, 0.9);
    if (state.expr==='surprise') em.setValue(BlendShapePresetName.A, 0.85);
  }
}

function applyAll(){ (currentVRM ? applyVRM() : applyProcedural()); configField.value = JSON.stringify(state); }

/* ===== UI wiring ===== */
Object.values(sliders).forEach(inp=>{
  inp.addEventListener('input', ()=>{ state[inp.id]=Number(inp.value); applyAll(); });
});
document.querySelectorAll('[data-exp]').forEach(b=>{
  b.addEventListener('click', ()=>{ state.expr=b.getAttribute('data-exp'); if (currentVRM) applyVRM(); applyAll(); });
});

/* ===== Model load ===== */
const modelInput = document.getElementById('modelFile');
modelInput.addEventListener('change', async (e)=>{
  const file = e.target.files?.[0]; if (!file) return;
  const url = URL.createObjectURL(file);
  const ext = file.name.split('.').pop().toLowerCase();

  clearModel(); proto.visible = false;
  hudStatus.textContent = 'Loading model...';
  try{
    const gltf = await (new GLTFLoader()).register(p=> new VRMLoaderPlugin(p)).loadAsync(url);
    if (ext === 'vrm'){
      const vrm = gltf.userData.vrm;
      VRMUtils.removeUnnecessaryVertices(vrm.scene); VRMUtils.removeUnnecessaryJoints(vrm.scene);
      vrm.scene.rotation.y = Math.PI; scene.add(vrm.scene); currentVRM = vrm; hudStatus.textContent = 'VRM model';
    } else {
      const obj = gltf.scene || gltf.scenes?.[0];
      obj.traverse(n=>{ if (n.isMesh && n.material){ n.material.roughness=.6; n.material.metalness=.02; } });
      scene.add(obj); currentGLTF = gltf; hudStatus.textContent = 'GLTF model';
    }
    applyAll();
  } catch(err){
    console.error(err); proto.visible = true; hudStatus.textContent = 'Procedural head (fallback)';
    alert('Failed to load model. Using procedural head.');
  }
});

/* ===== Export / Recenter / Reset ===== */
document.getElementById('btnExport').addEventListener('click', ()=>{
  const a = document.createElement('a'); a.href = renderer.domElement.toDataURL('image/png'); a.download = 'avatar.png'; a.click();
});
document.getElementById('btnRecenter').addEventListener('click', ()=>{ controls.target.set(0,1.5,0); camera.position.set(0,1.55,3.1); controls.update(); });
document.getElementById('btnReset').addEventListener('click', ()=>{
  Object.assign(state, { faceShape:50, jawWidth:50, eyeSize:48, mouthWidth:60, realism:40, expr:'neutral' });
  Object.entries(sliders).forEach(([k,el])=> el.value = state[k]);
  if (currentVRM?.expressionManager) currentVRM.expressionManager.rebind();
  applyAll();
});

/* ===== Resize ===== */
function resize(){
  const w = elStage.clientWidth || hero.clientWidth;
  const h = elStage.clientHeight || hero.clientHeight;
  renderer.setSize(w, h, false);
  composer.setSize(w, h);
  camera.aspect = w/h; camera.updateProjectionMatrix();
}
new ResizeObserver(resize).observe(hero);
window.addEventListener('load', resize);

/* ===== Loop & FPS ===== */
let last = performance.now(), fsum=0, fcnt=0, ftimer=0;
function tick(now){
  const dt = (now - last)/1000; last = now;
  controls.update();

  // micro breathing / eye drift
  if (!currentVRM){
    const t = now*0.0013;
    head.position.y = 1.5 + Math.sin(t)*0.01; jaw.position.y  = 1.1 + Math.sin(t+1)*0.008;
    const irisDrift = 0.01;
    irisL.position.x = -0.26 + Math.sin(t*1.7)*irisDrift; irisR.position.x =  0.26 + Math.sin(t*1.7)*irisDrift;
  } else {
    currentVRM.update(dt);
  }

  composer.render();

  // fps hud
  fsum += 1/dt; fcnt++; ftimer += dt;
  if (ftimer > 0.5){ hudFps.textContent = 'FPS ' + Math.round(fsum/fcnt); fsum=0; fcnt=0; ftimer=0; }

  requestAnimationFrame(tick);
}
applyAll();
requestAnimationFrame(tick);

/* ====== Selfie & FaceMesh (on-device) ====== */
const selfieInput = document.getElementById('selfie');
const selfieCanvas = document.getElementById('selfieCanvas');
const selfieCtx = selfieCanvas.getContext('2d');
const selfiePreview = document.getElementById('selfiePreview');
const btnAuto = document.getElementById('btnAuto');
let faceMesh = null;

function ensureFaceMesh(){
  if (faceMesh) return;
  faceMesh = new window.FaceMesh.FaceMesh({
    locateFile:(f)=>`https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/${f}`
  });
  faceMesh.setOptions({ maxNumFaces:1, refineLandmarks:true, minDetectionConfidence:.5, minTrackingConfidence:.5 });
  faceMesh.onResults(onLandmarks);
}
selfieInput.addEventListener('change', (e)=>{
  const file = e.target.files?.[0]; if (!file) return;
  const url = URL.createObjectURL(file);
  const img = new Image();
  img.onload = ()=>{ selfieCanvas.width = img.width; selfieCanvas.height = img.height; selfieCtx.drawImage(img, 0, 0);
    selfiePreview.src = selfieCanvas.toDataURL('image/png'); };
  img.src = url;
});
btnAuto.addEventListener('click', async ()=>{
  if (!selfieCanvas.width){ alert('Please choose a selfie first.'); return; }
  ensureFaceMesh(); await faceMesh.send({ image: selfieCanvas });
});
function onLandmarks(res){
  if (!res.multiFaceLandmarks || !res.multiFaceLandmarks.length) { alert('No face detected.'); return; }
  const lm = res.multiFaceLandmarks[0];
  const L = lm[234], R = lm[454], chin = lm[152], forehead = lm[10];
  const dx = Math.hypot(R.x - L.x, R.y - L.y);
  const dy = Math.hypot(chin.x - forehead.x, chin.y - forehead.y);

  const ratio = dy / dx; // >1 => long
  state.faceShape = Math.max(0, Math.min(100, (ratio - 0.9) * 200)); sliders.faceShape.value = Math.round(state.faceShape);
  state.jawWidth = Math.max(0, Math.min(100, dx * 120));             sliders.jawWidth.value = Math.round(state.jawWidth);

  const eyeTop = lm[386], eyeBot = lm[374];
  const eyeOpen = Math.hypot(eyeTop.x - eyeBot.x, eyeTop.y - eyeBot.y);
  state.eyeSize = Math.max(20, Math.min(80, 20 + eyeOpen * 600));    sliders.eyeSize.value = Math.round(state.eyeSize);

  const lipL = lm[61], lipR = lm[291];
  const mouthW = Math.hypot(lipL.x - lipR.x, lipL.y - lipR.y);
  state.mouthWidth = Math.max(20, Math.min(100, 20 + mouthW * 400)); sliders.mouthWidth.value = Math.round(state.mouthWidth);

  applyAll();
}
</script>
@endsection
