<div class="card">
    <div class="card-content">
        <div class="card-body">
            <h4 class="card-title">Vessel Information</h4>
                <div class="row">
                    <div class="col-md-3 mb-1">
                        <label for="vessel_name">Name</label>
                        <input type="text" id="vessel_name" name="vessel_name" class="form-control" value="{{isset($vessel_info)?$vessel_info->name:''}}" readonly>
                    </div>
                    <div class="col-md-3 mb-1">
                        <label for="vessel_type">Vessel Type</label>
                        <input type="text" id="vessel_type" name="vessel_type" class="form-control" value="{{isset($vessel_info->vessel_type)?$vessel_info->vessel_type->name:''}}" readonly>
                    </div>
                    <div class="col-md-3 mb-1">
                        <label for="build_year">Build Year</label>
                        <input type="text" id="build_year" name="build_year" class="form-control" value="{{isset($vessel_info)?$vessel_info->build_year:''}}" readonly>
                    </div>
                    <div class="col-md-3 mb-1">
                        <label for="bhp">BHP</label>
                        <input type="text" id="bhp" name="bhp" class="form-control" value="{{isset($vessel_info)?$vessel_info->bhp:''}}" readonly>
                    </div>
                </div>
            <div class="row">
                <div class="col-md-3 mb-1">
                    <label for="grt">GRT</label>
                    <input type="text" id="grt" name="grt" class="form-control" value="{{isset($vessel_info)?$vessel_info->grt:''}}" readonly>
                </div>
                <div class="col-md-3 mb-1">
                    <label for="breadth">breadth</label>
                    <input type="text" id="breadth" name="breadth" class="form-control" value="{{isset($vessel_info)?$vessel_info->breadth:''}}" readonly>
                </div>
                <div class="col-md-3 mb-1">
                    <label for="depth">Depth</label>
                    <input type="text" id="depth" name="depth" class="form-control" value="{{isset($vessel_info)?$vessel_info->depth:''}}" readonly>
                </div>
                <div class="col-md-3 mb-1">
                    <label for="incharge">Incharge</label>
                    <input type="text" id="incharge" name="incharge" class="form-control" value="{{isset($vessel_info->employee)?$vessel_info->employee->emp_name:''}}" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-1">
                    <label for="grt">Current Reserved Fuel(Ltr)</label>
                    <input type="text" id="reserved_fuel" name="reserved_fuel" class="form-control" style="background-color:skyblue;color: black;" value="{{isset($vessel_info)?$vessel_info->reserved_fuel:''}}" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
