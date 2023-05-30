<div class="card">
    <div class="card-content">
        <div class="card-body">
            <h4 class="card-title">Employee Info</h4>
                <div class="row">
                    <div class="col-md-3 mb-1">
                        <label for="emp_code">Emp Code</label>
                        <input type="text" id="emp_code" name="emp_code" class="form-control" value="{{isset($employee->employee)?$employee->employee->emp_code:''}}" readonly>
                    </div>
                    <div class="col-md-3 mb-1">
                        <label for="emp_code">Emp Name</label>
                        <input type="text" id="emp_name" name="emp_name" class="form-control" value="{{isset($employee->employee)?$employee->employee->emp_name:''}}" readonly>
                    </div>
                    <div class="col-md-3 mb-1">
                        <label for="designation">Designation</label>
                        <input type="text" id="designation" name="designation" class="form-control" value="{{isset($employee->designation)?$employee->designation->designation:''}}" readonly>
                    </div>
                </div>
        </div>
    </div>
</div>
