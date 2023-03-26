const BsModal = {
    confirm({
        title = '',
        content = '',
        type = 'primary',
        confirm = true,
        icon = 'fa-regular fa-circle-question',
        okText = 'Si',
        cancelText = 'No',
        onOk = ()=> {},
        onCancel = ()=> {}
    }) {
        const uniqueId = document.querySelectorAll('.modal').length + 1;

        const elemento = document.createElement('div');
        const cancelBtnHtml = confirm ? `<button type="button" id="cancel${uniqueId}" class="btn btn-secondary" data-bs-dismiss="modal">${cancelText}</button>` : '';
        elemento.innerHTML = `<div class="modal fade" id="staticBackdrop${uniqueId}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body text-center">
              <div class="fs-1 text-${type}"><i class="${icon}"></i></div>
              <h1 class="modal-title fs-5" id="staticBackdropLabel">${title}</h1>
              <div>${content}</div>
              <div class="mt-4">
                ${cancelBtnHtml}
                <button type="button" id="ok${uniqueId}" class="btn btn-${type}">${okText}</button>
              </div>
            </div>
          </div>
        </div>
      </div>`;

        document.body.appendChild(elemento);
        const myModalEle = document.getElementById('staticBackdrop' + uniqueId);
        const myModal = new bootstrap.Modal(myModalEle)
        myModal.show();

        const cancelBtn = document.getElementById('cancel' + uniqueId);
        if(cancelBtn){
          cancelBtn.addEventListener('click', (e) => {
              e.preventDefault();
              onCancel();
              myModal.hide();
          });
        }
        
        document.getElementById('ok' + uniqueId).addEventListener('click', (e) => {
            e.preventDefault();
            onOk();
            myModal.hide();
        });

        myModalEle.addEventListener('hidden.bs.modal', (e)=> {
          e.preventDefault();
          elemento.remove();
        });
    },
    success(params){
      this.confirm({
        icon: 'fa-solid fa-check',
        type: 'success',
        confirm: false,
        okText: 'Aceptar',
        ...params
      });
    },
    warning(params){
      this.confirm({
        icon: 'fa-solid fa-triangle-exclamation',
        type: 'warning',
        confirm: false,
        okText: 'Aceptar',
        ...params
      });
    },
    danger(params){
      this.confirm({
        icon: 'fa-solid fa-bug',
        type: 'danger',
        confirm: false,
        okText: 'Aceptar',
        ...params
      });
    },
}