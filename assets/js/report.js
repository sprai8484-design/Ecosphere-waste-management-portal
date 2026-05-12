const startCameraBtn = document.getElementById("start-camera");
const captureBtn = document.getElementById("capture-btn");
const uploadBtn = document.getElementById("upload-btn");

const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const placeholder = document.getElementById("camera-placeholder");
const fileInput = document.getElementById("file-input");

let stream = null;

/* ================= START CAMERA ================= */
startCameraBtn.addEventListener("click", async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: "environment" },
            audio: false
        });

        video.srcObject = stream;
        video.style.display = "block";
        placeholder.style.display = "none";
        captureBtn.disabled = false;

    } catch (err) {
        alert("Camera access denied or not available.");
        console.error(err);
    }
});

/* ================= CAPTURE IMAGE ================= */
captureBtn.addEventListener("click", () => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext("2d");
    ctx.drawImage(video, 0, 0);

    canvas.toBlob(blob => {
        const file = new File([blob], "captured-image.jpg", {
            type: "image/jpeg"
        });

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;

        // Show captured image
        video.style.display = "none";
        const img = document.createElement("img");
        img.src = URL.createObjectURL(blob);
        img.style.width = "100%";
        img.style.height = "100%";
        img.style.objectFit = "cover";

        document.getElementById("camera-preview").innerHTML = "";
        document.getElementById("camera-preview").appendChild(img);

        // Stop camera
        stream.getTracks().forEach(track => track.stop());
    });
});

/* ================= UPLOAD IMAGE ================= */
uploadBtn.addEventListener("click", () => {
    fileInput.click();
});

fileInput.addEventListener("change", () => {
    if (fileInput.files.length > 0) {
        const img = document.createElement("img");
        img.src = URL.createObjectURL(fileInput.files[0]);
        img.style.width = "100%";
        img.style.height = "100%";
        img.style.objectFit = "cover";

        document.getElementById("camera-preview").innerHTML = "";
        document.getElementById("camera-preview").appendChild(img);
    }
});
const startCameraBtn = document.getElementById('start-camera');
const captureBtn = document.getElementById('capture-btn');
const uploadBtn = document.getElementById('upload-btn');

const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const previewImg = document.getElementById('image-preview');
const fileInput = document.getElementById('file-input');
const placeholder = document.getElementById('camera-placeholder');

let stream = null;

/* START CAMERA */
startCameraBtn.addEventListener('click', async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
        video.style.display = 'block';
        placeholder.style.display = 'none';
        previewImg.style.display = 'none';
        captureBtn.disabled = false;
    } catch (err) {
        alert('Camera access denied');
    }
});

/* CAPTURE IMAGE */
captureBtn.addEventListener('click', () => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);

    const imageData = canvas.toDataURL('image/png');
    previewImg.src = imageData;
    previewImg.style.display = 'block';
    video.style.display = 'none';

    // stop camera
    stream.getTracks().forEach(track => track.stop());

    // convert canvas to file
    canvas.toBlob(blob => {
        const file = new File([blob], 'captured.png', { type: 'image/png' });
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
    });
});

/* UPLOAD IMAGE */
uploadBtn.addEventListener('click', () => {
    fileInput.click();
});

/* PREVIEW UPLOADED IMAGE */
fileInput.addEventListener('change', () => {
    const file = fileInput.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src = e.target.result;
        previewImg.style.display = 'block';
        video.style.display = 'none';
        placeholder.style.display = 'none';
    };
    reader.readAsDataURL(file);
});
