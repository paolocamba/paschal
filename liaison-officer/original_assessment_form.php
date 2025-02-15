<!-- Separate Modal for Each Row - Only shown when view button is clicked -->
<?php 
                if ($result->num_rows > 0):
                    $result->data_seek(0); // Reset result pointer
                    while($row = $result->fetch_assoc()): 
                ?>
                    <div class="modal fade" id="viewModal<?php echo $row['LoanID']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewModalLabel">Collateral Assessment</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    // Fetch collateral details for this loan
                                    $collateral_sql = "SELECT * FROM collateral_info WHERE LoanID = ?";
                                    $collateral_stmt = $conn->prepare($collateral_sql);
                                    $collateral_stmt->bind_param("s", $row['LoanID']);
                                    $collateral_stmt->execute();
                                    $collateral_info = $collateral_stmt->get_result()->fetch_assoc();
                                    ?>
                                    <form id="collateralForm<?php echo $row['LoanID']; ?>" class="collateral-form">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-title">Land Title</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th width="25%">Property Detail</th>
                                                                <th width="25%">Borrower Input</th>
                                                                <th width="25%">Validator Input</th>
                                                                <th width="25%">Result</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Square Meters Row -->
                                                            <tr>
                                                                <td>Square Meters</td>
                                                                <td>
                                                                    <input type="number" class="form-control" name="square_meters" 
                                                                        value="<?php echo htmlspecialchars($collateral_info['square_meters'] ?? ''); ?>" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control" name="validator_square_meters">
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="result_square_meters" class="form-control result-cell" readonly>
                                                                </td>
                                                            </tr>
                                                            <!-- Type of Land Row -->
                                                            <tr>
                                                                <td>Type of Land</td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="type_of_land" 
                                                                        value="<?php echo htmlspecialchars($collateral_info['type_of_land'] ?? ''); ?>" readonly>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control" name="validator_land_type" required>
                                                                        <option value="">Select</option>
                                                                        <option value="INDUSTRIAL">Industrial</option>
                                                                        <option value="RESIDENTIAL">Residential</option>
                                                                        <option value="AGRICULTURAL">Agricultural</option>
                                                                        <option value="COMMERCIAL">Commercial</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="result_land_type" class="form-control result-cell" readonly>
                                                                </td>
                                                            </tr>
                                                            <!-- Location Row -->
                                                            <tr>
                                                                <td>Location</td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="location_name"
                                                                        value="<?php echo htmlspecialchars($collateral_info['location_name'] ?? ''); ?>" readonly>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control" name="validator_location" required>
                                                                    <option value="">Select</option>
                                                                        <?php
                                                                        $location_sql = "SELECT name FROM locations ORDER BY name";
                                                                        $location_result = $conn->query($location_sql);
                                                                        while ($loc = $location_result->fetch_assoc()) {
                                                                            echo "<option value='" . htmlspecialchars($loc['name']) . "'>" . 
                                                                                htmlspecialchars($loc['name']) . "</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="result_location" class="form-control result-cell" readonly>
                                                                </td>
                                                            </tr>

                                                            <!-- Right of Way Row -->
                                                            <tr>
                                                                <td>Right of Way</td>
                                                                <td>
                                                                    <input type="text" class="form-control borrower-input" name="right_of_way"
                                                                        value="<?php echo htmlspecialchars($collateral_info['right_of_way'] ?? ''); ?>" readonly>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control" name="validator_right_of_way">
                                                                        <option value="">Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="result_right_of_way" class="form-control result-cell" readonly>
                                                                </td>
                                                            </tr>
                                                            <!-- Proximity Fields -->
                                                            <?php
                                                            $proximity_fields = [
                                                                ['hospital', 'Hospital', 'has_hospital'],
                                                                ['clinic', 'Clinic', 'has_clinic'],
                                                                ['school', 'School', 'has_school'],
                                                                ['market', 'Market', 'has_market'],
                                                                ['church', 'Church', 'has_church'],
                                                                ['public_terminal', 'Public Terminal', 'has_terminal']
                                                            ];

                                                            foreach ($proximity_fields as $field):
                                                                $field_name = $field[0];
                                                                $display_name = $field[1];
                                                                $db_field = $field[2];
                                                                $field_value = $collateral_info[$db_field] ?? 'No';
                                                                $display_value = $field_value === 'Yes' ? '< 1KM' : '> 1KM';
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $display_name; ?></td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="<?php echo $db_field; ?>" 
                                                                        value="<?php echo htmlspecialchars($field_value); ?>" readonly>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control" name="validator_<?php echo $field_name; ?>">
                                                                        <option value="">Select</option>
                                                                        <option value="No">> 1KM</option>
                                                                        <option value="Yes">< 1KM</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="result_<?php echo $field_name; ?>" 
                                                                        class="form-control result-cell" readonly>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                    
                                                    <!-- Summary Table -->
                                                    <table class="table table-bordered mt-4">
                                                        <tbody>
                                                            <tr>
                                                                <td width="25%">Final Zonal Value</td>
                                                                <td>
                                                                    <input type="number" class="form-control" name="final_zonal_value" 
                                                                        value="<?php echo htmlspecialchars($collateral_info['final_zonal_value'] ?? ''); ?>" readonly>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>EMV (Per Sqm)</td>
                                                                <td>
                                                                    <input type="number" class="form-control" name="emv_per_sqm" 
                                                                        value="<?php echo htmlspecialchars($collateral_info['emv_per_sqm'] ?? ''); ?>" readonly>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total Value</td>
                                                                <td>
                                                                    <input type="number" class="form-control" name="total_value" 
                                                                        value="<?php echo htmlspecialchars($collateral_info['total_value'] ?? ''); ?>" readonly>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Loanable Value</td>
                                                                <td>
                                                                    <input type="number" class="form-control" name="loanable_value" 
                                                                        value="<?php echo htmlspecialchars($collateral_info['loanable_value'] ?? ''); ?>" readonly>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <!-- Image Upload Section -->
                                                    <div class="form-group mt-4">
                                                        <label>Upload Property Images</label>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="property_images[]" multiple accept="image/*">
                                                            <label class="custom-file-label">Choose files</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" onclick="submitValidatorAssessment(<?php echo $row['LoanID']; ?>)">Submit Assessment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                    endwhile;
                endif;
                ?>