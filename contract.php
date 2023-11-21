if ($this->request->has('txtLimit_date_start') && $this->request->has('txtLimit_date_end') && $_POST['txtLimit_date_end'] != '') {
    $validate['txtLimit_date_start'] = 'before:txtLimit_date_end|date|nullable';
}
if ($this->has('txtTrial_date_start') && $this->has('txtTrial_date_end') && $_POST['txtTrial_date_end'] != '') {
    $validate['txtTrial_date_start'] = 'before:txtTrial_date_end|date|nullable';
}
