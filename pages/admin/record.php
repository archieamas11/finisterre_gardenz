                <div class="container-fluid">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 font-weight-bold d-flex">
                                <span class="mr-auto">Deceased Info</span>
                                <span><a href="index.php?page=insert"><button class="btn btn-secondary btn-sm">Insert</button></a></span>
                                <!-- <span><a href="index.php?page=merge_record"><button class="btn btn-outline-secondary btn-sm">Merge</button></a></span> -->
                            </p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                                <table class="table my-0" id="deceased-table">
                                    <?php
                                    $result = mysqli_query($mysqli, "SELECT * FROM grave_record 
                                        LEFt JOIN grave_points ON grave_record.grave_id=grave_points.grave_id WHERE status = 'occupied1' OR status = 'occupied2' OR status = 'occupied3'");
                                    ?>
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Grave no.</th>
                                            <th>Gender</th>
                                            <th>Birth Date</th>
                                            <th>Death Date</th>
                                            <th>Age Group</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = mysqli_fetch_array($result)) {
                                            $nametrim = str_replace(',', '</br>', $row['record_name']);
                                            $birthtrim = str_replace(',', '</br>', $row['record_birth']);
                                            $deathtrim = str_replace(',', '</br>', $row['record_death']);
                                            echo '<tr>';
                                            echo '<td>' . $nametrim . '</td>';
                                            echo '<td>' . $row['grave_id'] . '</td>';
                                            echo '<td>' . $row['record_gender'] . '</td>';
                                            echo '<td>' . $birthtrim . '</td>';
                                            echo '<td>' . $deathtrim . '</td>';
                                            echo '<td>' . $row['record_agegroup'] . '</td>';
                                            echo '<td>
                                                    <div class="dropdown">
                                                         <button class="btn btn-secondary dropdown-toggle me-1" type="button"
                                                                id="dropdownMenuButtonSec" data-bs-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                Secondary
                                                        </button>


                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonSec">
                                                            <a class="dropdown-item" href="index.php?name=' . $nametrim . ' & page=contact_person">Contact Person">Contact Person</a>
                                                            <a class="dropdown-item" href="index.php?id=' . $row['record_id'] . ' & page=edit_record">Edit</a>
                                                        </div>                 
                                                    </div>
                                                    </td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>