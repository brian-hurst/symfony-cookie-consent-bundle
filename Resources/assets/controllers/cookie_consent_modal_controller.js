import { Controller } from '@hotwired/stimulus';
window.bootstrap = require('bootstrap')

export default class extends Controller {
  static targets = ['open']
  static domElement
  static bsModal

  initialize() {
    this.domElement = document.getElementById('cookieconsent')
    console.log('LOADED!!');
    if (this.domElement) {
      this.buildModal()
      this.eventFormSubmit()
    }
  }

  connect() {
    console.log('LOADED!!');
    if (this.openTarget.dataset.fnOpen == 'true') {
      this.show()
    }
  }

  buildModal() {
    this.bsModal = new bootstrap.Modal(this.domElement, {
      backdrop: false,
      keyboard: false,
      focus: true,
    })
  }

  show() {
    console.log("CONTROLLER CALLED");
    this.bsModal.show()
  }

  eventFormSubmit() {
    document.addEventListener('cookie-consent-form-submit-successful', (e) => {
      this.bsModal.hide()
    })
  }
}
