  <!------------------------------- INTERNMENT FOR DECEASED MODAL -------------------------->
  <?php while($row=$sql_deceased->fetch_array()){ ?>
    <div class="modal fade" id="add-deceased-<?php echo $row['lot_owner_id']?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-xl"> 
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title d-flex align-items-center" id="staticBackdropLabel">
              <i class='bx bx-user-x fs-1'></i>
              &nbsp;Add deceased relative of <?php echo $row['first_name']." ".$row['family_name']?>.
            </h4>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" method="post" enctype="multipart/form-data">
            <div class="modal-body p-5">
              <div class="">
                <div class="row mb-3">
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-family-name">Family name:<i class="req">*</i></label>
                    <input type="text" name="dead-family-name" id="dead-family-name" class="form-control" placeholder="Surname" required>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-first-name">First name:<i class="req">*</i></label>
                    <input type="text" name="dead-first-name" id="dead-first-name" class="form-control" placeholder="Given name" required>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-middle-name">Middle name:</label>
                    <input type="text" name="dead-middle-name" id="dead-middle-name" class="form-control" placeholder="Middle name">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-gender">Gender:<i class="req">*</i></label>
                    <select type="text" name="dead-gender" id="dead-gender" class="form-select" required>
                      <option value="" selected disabled>Select gender</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-citizenship">Citizenship:<i class="req">*</i></label>
                    <input type="text" name="dead-citizenship" id="dead-citizenship" placeholder="Citizenship" class="form-control" required>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-civil-status">Civil Status:<i class="req">*</i></label>
                    <select type="text" name="dead-civil-status" id="dead-civil-status" class="form-select" required>
                      <option value="" disabled selected>Civil Status</option>
                      <option value="Single">Single</option>
                      <option value="Married">Married</option>
                      <option value="Widowed">Widowed</option>
                      <option value="Seperated">Seperated</option>
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-relative">Relative:<i class="req">*</i></label>
                    <input type="text" name="dead-relative" id="dead-relative" placeholder="First name" class="form-control" value="<?php echo $row['first_name']?>" readonly required>
                    <input type="hidden" id="lot-owner-id" value="<?php echo $row['lot_owner_id']?>" name="lot-owner-id">
                    <input type="hidden" id="customer-id" value="<?php echo $row['customer_id']?>" name="customer-id">
                    <input type="hidden" id="site-id" value="<?php echo $row['site_id']?>" name="site-id">
                    <input type="hidden" id="block-id" value="<?php echo $row['block_id']?>" name="block-id">
                    <input type="hidden" id="lot-id" value="<?php echo $row['lot_id']?>" name="lot-id">
                    <small class="text-danger" id="relative-error"></small>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-relative-surname"></label>
                    <input type="text" name="dead-relative-surname" id="dead-relative-surname" placeholder="Surname" class="form-control" value="<?php echo $row['family_name']?>" readonly required>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-relationship">Relationship:<i class="req">*</i></label>
                    <select type="text" name="dead-relationship" id="dead-relationship" class="form-select" required>
                      <option value="" disabled selected>Relationship</option>
                      <option value="Child">Child</option>
                      <option value="Father">Father</option>
                      <option value="Mother">Mother</option>
                      <option value="Sibling">Sibling</option>
                      <option value="Spouse">Spouse</option>
                      <option value="Grandparent">Grandparent</option>
                      <option value="Grandchild">Grandchild</option>
                      <option value="Parent's sibling">Parent's sibling</option>
                      <option value="Sibling's child">Sibling's child</option>
                      <option value="Cousin">Cousin</option>
                      <option value="Father-In-Law">Father-In-Law</option>
                      <option value="Mother-In-Law">Mother-In-Law</option>
                      <option value="Brother-In-Law">Brother-In-Law</option>
                      <option value="Sister-In-Law">Sister-In-Law</option>
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-intern">Internment Date <i class="req">*</i></label>
                    <input type="date" name="dead-intern" id="dead-intern" class="form-control" required>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-bday">Date of Birth:<i class="req">*</i></label>
                    <input type="date" name="dead-bday" id="dead-bday" class="form-control" required>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <label for="dead-death">Date of Death:<i class="req">*</i></label>
                    <input type="date" name="dead-death" id="dead-death" class="form-control" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-6 col-sm-6">
                    <label for="death-cert">Death Certificate:<i class="req">*</i></label>
                    <input type="file" name="death-cert" id="death-cert" class="form-control" accept=".pdf, .docx, .jpg, .png" required>
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <label for="burial-permit">Burial Permit:<i class="req">*</i></label>
                    <input type="file" name="burial-permit" id="burial-permit" class="form-control" accept=".pdf, .docx, .jpg, .png" required>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="btn-submit-dead" id="btn-submit-dead" class="btn btn-primary">Register</button>
              <button type="reset" class="btn btn-danger">Reset</button> 
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php } ?>