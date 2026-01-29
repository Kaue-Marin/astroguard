<script setup>
import { onMounted, ref } from 'vue';
import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';

const containerRef = ref(null);

onMounted(() => {
  const scene = new THREE.Scene();
  
  const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
  camera.position.z = 2.5;

  const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
  renderer.setSize(window.innerWidth, window.innerHeight);
  renderer.setPixelRatio(window.devicePixelRatio);
  
  if (containerRef.value) {
    containerRef.value.innerHTML = ''; 
    containerRef.value.appendChild(renderer.domElement);
  }

  const controls = new OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true;
  controls.dampingFactor = 0.05;

  const textureLoader = new THREE.TextureLoader();
  const earthGroup = new THREE.Group();
  earthGroup.rotation.z = -23.4 * Math.PI / 180;
  scene.add(earthGroup);

  const geometry = new THREE.SphereGeometry(1, 64, 64);

  const material = new THREE.MeshPhongMaterial({
    map: textureLoader.load('/textures/cor.png'),        
    normalMap: textureLoader.load('/textures/relevo.png'),
    specularMap: textureLoader.load('/textures/brilho.png'), 
    specular: new THREE.Color(0x333333),
    shininess: 15
  });

  const earthMesh = new THREE.Mesh(geometry, material);
  earthGroup.add(earthMesh);

  const sunLight = new THREE.DirectionalLight(0xffffff, 2.0);
  sunLight.position.set(5, 3, 5);
  scene.add(sunLight);

  const ambientLight = new THREE.AmbientLight(0x404040, 1.0); 
  scene.add(ambientLight);

  function animate() {
    requestAnimationFrame(animate);
    earthMesh.rotation.y += 0.002;
    controls.update();
    renderer.render(scene, camera);
  }

  animate();
    window.addEventListener('resize', () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
  });
});
</script>

<template>
  <div ref="containerRef" id="canvas-container"></div>
</template>

<style>
body { 
  margin: 0; 
  overflow: hidden; 
  background-color: #000; 
  background-image: radial-gradient(circle at center, #1a1f35 0%, #000000 100%);
}

#canvas-container {
  width: 100vw;
  height: 100vh;
  display: block;
}
</style>