var imgUpload = document.getElementById('upload-img'),
    imgPreview = document.getElementById('img-preview'),
    imgUploadForm = document.getElementById('form-upload'),
    totalFiles,
    previewTitle,
    previewTitleText,
    img;

imgUpload.addEventListener('change', previewImgs, true);

function previewImgs(event) {
    // Limpa o conteúdo da div img-preview antes de adicionar a nova imagem
    imgPreview.innerHTML = '';

    totalFiles = imgUpload.files.length;

    if (!!totalFiles) {
        imgPreview.classList.remove('img-thumbs-hidden');
    }

    for (var i = 0; i < totalFiles; i++) {
        var wrapper = document.createElement('div');
        wrapper.classList.add('wrapper-thumb');

        var removeBtn = document.createElement('span');
        var nodeRemove = document.createTextNode('x');
        removeBtn.classList.add('remove-btn');
        removeBtn.appendChild(nodeRemove);$(document).on('click', '.remove-btn', function() {
          // Remove a imagem dentro da div .wrapper-thumb
          $(this).parent('.wrapper-thumb').remove();
      
          // Verifica se a div #img-preview está vazia
          if ($('#img-preview').children().length === 0) {
              // Oculta a div #img-preview se estiver vazia
              $('#img-preview').hide();
      
              // Reseta o input de upload criando um clone e reanexando o evento change
              const imgUpload = $('#upload-img');
              const newImgUpload = imgUpload.clone().val(''); // Clona o input e limpa o valor
              imgUpload.replaceWith(newImgUpload);
      
              // Reanexa o evento change ao novo input clonado
              newImgUpload.on('change', previewImgs);
          }
      });
      
      function previewImgs(event) {
          // Limpa o conteúdo da div img-preview antes de adicionar a nova imagem
          $('#img-preview').html('');
      
          const imgUpload = document.getElementById('upload-img');
          const totalFiles = imgUpload.files.length;
      
          if (totalFiles > 0) {
              $('#img-preview').show(); // Mostra a div img-preview caso ela esteja oculta
          }
      
          for (let i = 0; i < totalFiles; i++) {
              const wrapper = document.createElement('div');
              wrapper.classList.add('wrapper-thumb');
      
              const removeBtn = document.createElement('span');
              removeBtn.classList.add('remove-btn');
              removeBtn.textContent = 'x';
      
              const img = document.createElement('img');
              img.src = URL.createObjectURL(event.target.files[i]);
              img.classList.add('img-preview-thumb');
      
              wrapper.appendChild(img);
              wrapper.appendChild(removeBtn);
              document.getElementById('img-preview').appendChild(wrapper);
          }
      }
      

        var img = document.createElement('img');
        img.src = URL.createObjectURL(event.target.files[i]);
        img.classList.add('img-preview-thumb');

        wrapper.appendChild(img);
        wrapper.appendChild(removeBtn);
        imgPreview.appendChild(wrapper);

        $('.remove-btn').click(function() {
          // Remove a imagem e a wrapper
          $(this).parent('.wrapper-thumb').remove();
          
          // Verifica se ainda há imagens dentro da div img-preview
          if ($('#img-preview .wrapper-thumb').length === 0) {
            // Oculta a div se não houver mais imagens
            $('#img-preview').hide();
          }
        }); 
    }
}

   
    

  
  
  


