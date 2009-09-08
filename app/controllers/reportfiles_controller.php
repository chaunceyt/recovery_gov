<?php
class ReportfilesController extends AppController {

	var $name = 'Reportfiles';
	var $helpers = array('Html', 'Form');

	function index() {
		$this->Reportfile->recursive = 0;
        $this->paginate['order'] = 'Reportfile.agency';
		$this->set('reportfiles', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Reportfile.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('reportfile', $this->Reportfile->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Reportfile->create();
			if ($this->Reportfile->save($this->data)) {
				$this->Session->setFlash(__('The Reportfile has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Reportfile could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Reportfile', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Reportfile->save($this->data)) {
				$this->Session->setFlash(__('The Reportfile has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Reportfile could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Reportfile->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Reportfile', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Reportfile->del($id)) {
			$this->Session->setFlash(__('Reportfile deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>
