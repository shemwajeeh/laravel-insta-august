// // Vite bundle entry
// import * as THREE from 'three';
// import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
// import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js';
// import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
// import { EffectComposer } from 'three/examples/jsm/postprocessing/EffectComposer.js';
// import { RenderPass } from 'three/examples/jsm/postprocessing/RenderPass.js';
// import { UnrealBloomPass } from 'three/examples/jsm/postprocessing/UnrealBloomPass.js';

// // ★ v2 の正しい export 名
// import {
//   VRMLoaderPlugin,
//   VRMUtils,
//   VRMExpressionPresetName, // ← 旧 BlendShapePresetName の置き換え
// } from '@pixiv/three-vrm';

// function $(id){ return document.getElementById(id); }

// const hero = $('hero');
// const elStage = $('stage');
// const hudStatus = $('hudStatus');
// const hudFps = $('hudFps');
// const configField = $('configField');

// const sliders = {
//   faceShape : $('faceShape'),
//   jawWidth  : $('jawWidth'),
//   eyeSize   : $('eyeSize'),
//   mouthWidth: $('mouthWidth'),
//   realism   : $('realism'),
// };
// const state = { faceShape:50, jawWidth:50, eyeSize:48, mouthWidth:60, realism:40, expr:'neutral' };

// /* ===== renderer / scene ===== */
// const renderer = new THREE.WebGLRenderer({ antialias:true, alpha:true, preserveDrawingBuffer:true });
// renderer.outputColorSpace = THREE.SRGBColorSpace;
// renderer.toneMapping = THREE.ACESFilmicToneMapping;
// renderer.toneMappingExposure = 1.18;
// elStage.appendChild(renderer.domElement);

// const scene = new THREE.Scene();
// const pmrem = new THREE.PMREMGenerator(renderer);
// scene.environment = pmrem.fromScene(new RoomEnvironment(renderer), 0.1).texture;
// scene.background = new THREE.Color(0x0b1020);

// const camera = new THREE.PerspectiveCamera(32, 1, 0.1, 100);
// camera.position.set(0, 1.55, 3.1);

// const controls = new OrbitControls(camera, renderer.domElement);
// controls.enableDamping = true; controls.dampingFactor = .05;
// controls.target.set(0, 1.5, 0);

// const hemi = new THREE.HemisphereLight(0xffffff, 0x223355, 0.85);
// scene.add(hemi);
// const dir = new THREE.DirectionalLight(0xffffff, 2.0);
// dir.position.set(2.2, 4.2, 2.4);
// scene.add(dir);

// /* ground ring */
// const floor = new THREE.Mesh(
//   new THREE.RingGeometry(0.6, 3.2, 64),
//   new THREE.MeshPhysicalMaterial({ color:0x0f172a, roughness:.95, metalness:.0, transmission:0, reflectivity:.04 })
// );
// floor.rotation.x = -Math.PI/2;
// floor.position.y = 0;
// scene.add(floor);

// /* postprocessing */
// const composer = new EffectComposer(renderer);
// composer.addPass(new RenderPass(scene, camera));
// const bloom = new UnrealBloomPass(new THREE.Vector2(1,1), 0.25, 0.6, 0.85);
// composer.addPass(bloom);

// /* procedural head */
// const skin = new THREE.MeshPhysicalMaterial({
//   color: 0xffd4c4, roughness: .38, metalness: .03, sheen: 1.0, sheenRoughness: .6, clearcoat: 0.35, clearcoatRoughness: .45,
// });
// const hairMat = new THREE.MeshPhysicalMaterial({ color: 0x1a1a1a, roughness: .85, metalness: .02, sheen: .6, sheenRoughness: .8 });
// const eyeWhite = new THREE.MeshPhysicalMaterial({ color: 0xffffff, roughness: .15 });
// const eyeIris  = new THREE.MeshPhysicalMaterial({ color: 0x111111, roughness: .65, metalness: .02 });

// const proto = new THREE.Group(); proto.name = 'Procedural';
// const head = new THREE.Mesh(new THREE.SphereGeometry(0.75, 96, 96), skin);   head.position.set(0,1.5,0);
// const jaw  = new THREE.Mesh(new THREE.SphereGeometry(0.60, 80, 80), skin);   jaw.position.set(0,1.1,0); jaw.scale.set(1.2,.6,1.1);
// const hair = new THREE.Mesh(new THREE.SphereGeometry(0.77, 96, 96), hairMat); hair.position.set(0,1.62,0); hair.scale.y = .64;
// const eyeLw = new THREE.Mesh(new THREE.SphereGeometry(0.15, 48, 48), eyeWhite); eyeLw.position.set(-0.26,1.5,0.6);
// const eyeRw = eyeLw.clone(); eyeRw.position.x *= -1;
// const irisL = new THREE.Mesh(new THREE.SphereGeometry(0.07, 32, 32), eyeIris); irisL.position.set(-0.26,1.5,0.75);
// const irisR = irisL.clone(); irisR.position.x *= -1;
// const mouth = new THREE.Mesh(new THREE.CapsuleGeometry(0.28, 0.02, 12, 24), new THREE.MeshPhysicalMaterial({ color:0xaa3355, roughness:.5 }));
// mouth.rotation.x = Math.PI/2; mouth.position.set(0,1.18,0.65);
// proto.add(head,jaw,hair,eyeLw,eyeRw,irisL,irisR,mouth);
// scene.add(proto);

// let currentVRM = null; let currentGLTF = null;

// /* loaders */
// const loader = new GLTFLoader();
// loader.register((parser)=> new VRMLoaderPlugin(parser));
// function clearModel(){
//   if (currentVRM){ scene.remove(currentVRM.scene); currentVRM.dispose(); currentVRM=null; }
//   if (currentGLTF){ scene.remove(currentGLTF.scene || currentGLTF); currentGLTF=null; }
// }

// /* mapping */
// function applyProcedural(){
//   const faceLong = THREE.MathUtils.lerp(0.92, 1.20, (state.faceShape)/100);
//   head.scale.set(1, faceLong, 1);
//   jaw.scale.set(THREE.MathUtils.lerp(0.9, 1.42, state.jawWidth/100), 0.6, 1.1);

//   const es = THREE.MathUtils.lerp(0.12, 0.2, (state.eyeSize-20)/60);
//   eyeLw.scale.setScalar(es/0.15); eyeRw.scale.setScalar(es/0.15);
//   irisL.scale.setScalar(THREE.MathUtils.lerp(0.7, 1.18, (state.eyeSize-20)/60));
//   irisR.scale.copy(irisL.scale);

//   const mw = THREE.MathUtils.lerp(0.2, 0.46, (state.mouthWidth-20)/80);
//   mouth.scale.set(mw/0.28, 1, 1);

//   const t = state.realism/100;
//   renderer.toneMappingExposure = THREE.MathUtils.lerp(1.05, 1.28, t);
//   skin.roughness = THREE.MathUtils.lerp(.42, .32, t);
//   skin.clearcoat = THREE.MathUtils.lerp(.25, .45, t);
//   dir.intensity = THREE.MathUtils.lerp(1.4, 2.2, t);

//   if (state.expr==='smile'){ mouth.position.y = 1.19; mouth.scale.y = 1.18; }
//   else if (state.expr==='surprise'){ mouth.scale.set(0.22/0.28, 2.2, 1); mouth.position.y = 1.2; }
//   else if (state.expr==='wink'){ eyeLw.scale.y = 0.16; irisL.scale.y = 0.16; }
//   else if (state.expr==='blink'){
//     const oyL=eyeLw.scale.y, oyR=eyeRw.scale.y, piL=irisL.scale.y, piR=irisR.scale.y;
//     eyeLw.scale.y=eyeRw.scale.y=0.12; irisL.scale.y=irisR.scale.y=0.12;
//     setTimeout(()=>{ eyeLw.scale.y=oyL; eyeRw.scale.y=oyR; irisL.scale.y=piL; irisR.scale.y=piR; state.expr='neutral'; }, 220);
//   } else { mouth.scale.set(1,1,1); mouth.position.y=1.18; }
// }

// function applyVRM(){
//   if (!currentVRM) return;

//   // 頭部スケールなど軽いパラ調整
//   const headNode = currentVRM.humanoid?.getBoneNode('head');
//   if (headNode){
//     headNode.scale.set(
//       THREE.MathUtils.lerp(0.95, 1.1, state.jawWidth/100),
//       THREE.MathUtils.lerp(0.95, 1.15, state.faceShape/100),
//       1
//     );
//   }
//   const t = state.realism/100;
//   renderer.toneMappingExposure = THREE.MathUtils.lerp(1.05, 1.28, t);
//   dir.intensity = THREE.MathUtils.lerp(1.4, 2.0, t);

//   // ★ v2: Expression は VRMExpressionPresetName を使う
//   const em = currentVRM.expressionManager;
//   if (em){
//     em.setValue(VRMExpressionPresetName.Happy, 0);
//     em.setValue(VRMExpressionPresetName.Aa, 0);
//     em.setValue(VRMExpressionPresetName.Surprised, 0);

//     if (state.expr === 'smile')      em.setValue(VRMExpressionPresetName.Happy, 0.9);
//     else if (state.expr === 'surprise'){
//       // 口の開き（Aa）と驚きの複合
//       em.setValue(VRMExpressionPresetName.Aa, 0.85);
//       em.setValue(VRMExpressionPresetName.Surprised, 0.6);
//     }
//     // wink / blink はモデル側プリセット名称に依存しやすいので簡易にとどめる
//   }
// }

// function applyAll(){
//   (currentVRM ? applyVRM() : applyProcedural());
//   configField.value = JSON.stringify(state);
// }

// /* UI wiring */
// Object.values(sliders).forEach(inp=>{
//   inp.addEventListener('input', ()=>{ state[inp.id]=Number(inp.value); applyAll(); });
// });
// document.querySelectorAll('[data-exp]').forEach(b=>{
//   b.addEventListener('click', ()=>{ state.expr=b.getAttribute('data-exp'); if (currentVRM) applyVRM(); applyAll(); });
// });

// /* model load */
// const modelInput = $('modelFile');
// modelInput.addEventListener('change', async (e)=>{
//   const file = e.target.files?.[0]; if (!file) return;
//   const url = URL.createObjectURL(file);
//   const ext = file.name.split('.').pop().toLowerCase();

//   clearModel(); proto.visible = false;
//   hudStatus.textContent = 'Loading model...';
//   try{
//     const gltf = await loader.loadAsync(url);
//     if (ext === 'vrm'){
//       const vrm = gltf.userData.vrm;
//       VRMUtils.removeUnnecessaryVertices(vrm.scene);
//       VRMUtils.removeUnnecessaryJoints(vrm.scene);
//       vrm.scene.rotation.y = Math.PI;
//       scene.add(vrm.scene);
//       currentVRM = vrm;
//       hudStatus.textContent = 'VRM model';
//     } else {
//       const obj = gltf.scene || gltf.scenes?.[0];
//       obj.traverse(n=>{ if (n.isMesh && n.material){ n.material.roughness=.6; n.material.metalness=.02; } });
//       scene.add(obj);
//       currentGLTF = gltf;
//       hudStatus.textContent = 'GLTF model';
//     }
//     applyAll();
//   } catch(err){
//     console.error(err);
//     proto.visible = true;
//     hudStatus.textContent = 'Procedural head (fallback)';
//     alert('Failed to load model. Using procedural head.');
//   }
// });

// /* export / recenter / reset */
// $('btnExport').addEventListener('click', ()=>{
//   const a = document.createElement('a');
//   a.href = renderer.domElement.toDataURL('image/png'); a.download = 'avatar.png'; a.click();
// });
// $('btnRecenter').addEventListener('click', ()=>{
//   controls.target.set(0,1.5,0); camera.position.set(0,1.55,3.1); controls.update();
// });
// $('btnReset').addEventListener('click', ()=>{
//   Object.assign(state, { faceShape:50, jawWidth:50, eyeSize:48, mouthWidth:60, realism:40, expr:'neutral' });
//   Object.entries(sliders).forEach(([k,el])=> el.value = state[k]);
//   if (currentVRM?.expressionManager) currentVRM.expressionManager.rebind?.();
//   applyAll();
// });

// /* resize */
// function resize(){
//   const w = elStage.clientWidth || hero.clientWidth;
//   const h = elStage.clientHeight || hero.clientHeight;
//   renderer.setSize(w, h, false);
//   composer.setSize(w, h);
//   camera.aspect = w/h; camera.updateProjectionMatrix();
// }
// new ResizeObserver(resize).observe(hero);
// window.addEventListener('load', resize);

// /* loop & fps */
// let last = performance.now(), fsum=0, fcnt=0, ftimer=0;
// function tick(now){
//   const dt = (now - last)/1000; last = now;
//   controls.update();

//   if (!currentVRM){
//     const t = now*0.0013;
//     head.position.y = 1.5 + Math.sin(t)*0.01;
//     jaw.position.y  = 1.1 + Math.sin(t+1)*0.008;
//     const irisDrift = 0.01;
//     irisL.position.x = -0.26 + Math.sin(t*1.7)*irisDrift;
//     irisR.position.x =  0.26 + Math.sin(t*1.7)*irisDrift;
//   } else {
//     currentVRM.update(dt);
//   }

//   composer.render();

//   fsum += 1/dt; fcnt++; ftimer += dt;
//   if (ftimer > 0.5){ hudFps.textContent = 'FPS ' + Math.round(fsum/fcnt); fsum=0; fcnt=0; ftimer=0; }

//   requestAnimationFrame(tick);
// }
// applyAll();
// requestAnimationFrame(tick);
