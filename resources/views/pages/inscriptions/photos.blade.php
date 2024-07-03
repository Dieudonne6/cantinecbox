@extends('layouts.master')
@section('content')

<div class="card-body mb-10">
    <div class="row">
        <div class="card col-md-8">

            <div class="dt-search">
            <label for="dt-search-0">1. Taper le nom de l'élève à rechercher</label>
            <input type="search" class="dt-input" id="dt-search-0" placeholder="" aria-controls="myTable">
            </div>
            <label>2. Sélectionner l'élève dans la liste ci-dessous</label>
            <div class="table-responsive" style="height: 100px; overflow: auto;">
           <table class="table">
                <thead style="position: sticky; top: 0; z-index: 1;">
                  <tr>
                    <th>Nom</th>
                    <th>Prénoms</th>
                    <th>Classe</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="table-active">
                    <td>130</td>
                    <td>05/06/2024</td>
                    <td>190 000</td>
                  </tr>
                  <tr>
                    <td>130</td>
                    <td>05/06/2024</td>
                    <td>190 000</td>
                  </tr>
                  <tr>
                    <td>130</td>
                    <td>05/06/2024</td>
                    <td>190 000</td>
                  </tr>
                  <tr>
                    <td>130</td>
                    <td>05/06/2024</td>
                    <td>190 000</td>
                  </tr>
                </tbody>
            </table>
            </div>
            <div class="container" style="display: flex; align-items: center; justify-content: center; height: 100%;">
                <button class="validated-button" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Valider</button>
            </div>
        </div>
        <div class="card col-md-4">

                <style>
                    .container {
                        width: 300px;
                        margin: 0 auto;
                        text-align: center;
                    }
            
                    .photo-frame {
                        border: 2px solid #ccc;
                        padding: 10px;
                        display: inline-block;
                        position: relative;
                        background-color: #f9f9f9;
                    }
            
                    .photo-frame img {
                        display: block;
                        margin: 0 auto;
                        max-width: 100%;
                        height: auto;
                    }
            
                    .photo-frame input[type="file"] {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        opacity: 0;
                        width: 100%;
                        height: 100%;
                        cursor: pointer;
                    }
            
                    .upload-label {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background-color: rgba(0, 0, 0, 0.5);
                        color: #fff;
                        padding: 5px 10px;
                        border-radius: 5px;
                        pointer-events: none;
                        opacity: 0;
                        transition: opacity 0.3s;
                    }
            
                    .photo-frame:hover .upload-label {
                        opacity: 1;
                    }
                </style>
                <title>Photo Frame File Input</title>

                <div class="container">
                    <h4>Capture de Photo</h4>
                    <video id="video" width="160" height="120" autoplay></video>
                    <button id="snap">Prendre une photo</button>
                    <canvas id="canvas" width="160" height="120" style="display:none;"></canvas>
                    <img id="photo" src="" alt="Votre photo" style="display:none;"/>
                    <div id="imageOptions" style="display:none;">
                        <button id="saveButton">Enregistrer</button>
                        <button id="deleteButton">Supprimer</button>
                    </div>
            
                    <h4>Télécharger un fichier depuis votre PC</h4>
                    <div class="photo-frame">
                        <img id="frameImage" src="placeholder.jpg" alt="Cadre photo">
                        <input type="file" id="fileInput">
                        <div class="upload-label">Cliquez pour télécharger une photo</div>
                    </div>
                </div>
            
                <script>
                    // Accéder à la caméra de l'utilisateur
                    const video = document.getElementById('video');
                    const canvas = document.getElementById('canvas');
                    const context = canvas.getContext('2d');
                    const photo = document.getElementById('photo');
                    const snap = document.getElementById('snap');
                    const saveButton = document.getElementById('saveButton');
                    const deleteButton = document.getElementById('deleteButton');
                    const imageOptions = document.getElementById('imageOptions');
            
                    navigator.mediaDevices.getUserMedia({ video: true })
                    .then(stream => {
                        video.srcObject = stream;
                    })
                    .catch(err => {
                        console.error("Erreur d'accès à la caméra: ", err);
                    });
            
                    // Capturer une photo
                    snap.addEventListener('click', () => {
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);
                        photo.src = canvas.toDataURL('image/png');
                        photo.style.display = 'block';
                        imageOptions.style.display = 'block';
                    });
            
                    // Enregistrer l'image
                    saveButton.addEventListener('click', async () => {
                        if (!window.showSaveFilePicker) {
                            alert('Votre navigateur ne supporte pas cette fonctionnalité.');
                            return;
                        }
            
                        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
                        const fileHandle = await window.showSaveFilePicker({
                            suggestedName: 'photo.png',
                            types: [
                                {
                                    description: 'Image PNG',
                                    accept: {'image/png': ['.png']}
                                },
                            ],
                        });
            
                        const writableStream = await fileHandle.createWritable();
                        await writableStream.write(blob);
                        await writableStream.close();
                        alert('Image enregistrée avec succès.');
                    });
            
                    // Supprimer l'image
                    deleteButton.addEventListener('click', () => {
                        photo.src = '';
                        photo.style.display = 'none';
                        imageOptions.style.display = 'none';
                    });
            
                    // Mettre à jour l'image dans le cadre lorsqu'un fichier est sélectionné
                    document.getElementById('fileInput').addEventListener('change', function() {
                        const file = this.files[0];
                        if (file) {
                            const url = URL.createObjectURL(file);
                            document.getElementById('frameImage').src = url;
                            URL.revokeObjectURL(url); // Libérer l'URL de l'objet
                        }
                    });
                </script>
                   
        </div>
    </div>
</div>

@endsection