// Use this code to pass csrf token to ajax function. you dont have to include this token in ajax data property. Just let it be
$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $('meta[name="_token"]').attr("content"),
        },
    });
});

function swal_confirm(type, id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            if (type == "user") {
                deleteUser(id);
            }
            if (type == "product") {
                deleteProduct(id);
            }
            if (type == "warehouse") {
                deleteWarehouse(id);
            }
        }
    });
}

function getUser(id) {
    $.ajax({
        type: "post",
        url: "edit_user",
        dataType: "json",
        data: {
            id: id,
        },
        success: function (data) {
            if (data) {
                $("#edit_customer_id").val(data.id);
                $("#edit_user_type").val(data.user_type);
                if (data.warehouse_id != 0) {
                    $("#edit_warehouse_id").val(data.warehouse_id);
                    $("#warehouse_content").show();
                } else {
                    $("#warehouse_content").hide();
                }

                $("#edit_customer_name").val(data.customer_name);
                $("#edit_customer_address").val(data.customer_address);
                $("#edit_customer_phone_no").val(data.customer_phone_no);
                $("#edit_customer_vat_no").val(data.customer_vat_no);
                $("#edit_customer_vat_no").val(data.customer_vat_no);

                $("#editUserModal").modal("show");
            }
        },
        error: function (error) {
            alert("An error occured." + JSON.stringify(error));
        },
    });
}

function deleteUser(id) {
    $.ajax({
        type: "post",
        url: "delete_user",
        dataType: "json",
        data: {
            id: id,
        },
        success: function (data) {
            if (data) {
                Swal.fire("Deleted!", "User has been deleted.", "success");
                window.location.href = "/customer";
            }
        },
        error: function (error) {
            alert("An error occured." + JSON.stringify(error));
        },
    });
}

function getProduct(id) {
    $("#warehouse_id").val("");
    $("#txtAvailbaleQty").text("");
    if (id == "") {
        Swal.fire("Oops!", "Select a product.", "warning");
    } else {
        $.ajax({
            type: "post",
            url: "edit_product",
            dataType: "json",
            data: {
                id: id,
            },
            success: function (data) {
                if (data) {
                    $("#product_id").val(data.data.id);
                    $("#edit_warehouse_id").val(data.data.warehouse_id);
                    $("#edit_product_name").val(data.data.product_name);
                    $("#edit_product_code").val(data.data.product_code);
                    $("#edit_product_unit").val(data.data.product_unit);
                    $("#edit_product_unit_price").val(
                        data.data.product_unit_price
                    );
                    $("#edit_stock_available").val(data.data.stock_available);
                    $("#editProductModal").modal("show");
                }
            },
            error: function (error) {
                alert("An error occured." + JSON.stringify(error));
            },
        });
    }
}

function getProductByCode(code) {
    $("#warehouse_id").val("");
    $("#txtAvailbaleQty").text("");
    if (code == "") {
        Swal.fire("Oops!", "Select a product.", "warning");
    } else {
        $.ajax({
            type: "post",
            url: "edit_product_by_code",
            dataType: "json",
            data: {
                code: code,
            },
            success: function (data) {
                if (data) {
                    if ($("#unit").length > 0 && $("#unit_price").length > 0) {
                        $("#unit").val(data.data.product.product_unit);
                        $("#unit_price").val(
                            data.data.product.product_unit_price
                        );

                        var content =
                            '<select id="warehouse_id" name="warehouse_id" class="form-control form-control-sm" onchange="getProductData($(this).val())" required>';
                        content =
                            content +
                            '<option value="">Select Warehouse</option>';
                        for (let i = 0; i < data.data.warehouses.length; i++) {
                            content =
                                content +
                                '<option value="' +
                                data.data.warehouses[i].warehouse.id +
                                '">' +
                                data.data.warehouses[i].warehouse.name +
                                "</option>";
                        }
                        content = content + "</select>";
                        $("#warehouse_content").html(content);
                    }
                }
            },
            error: function (error) {
                alert("An error occured." + JSON.stringify(error));
            },
        });
    }
}

function deleteProduct(id) {
    $.ajax({
        type: "post",
        url: "delete_product",
        dataType: "json",
        data: {
            id: id,
        },
        success: function (data) {
            if (data) {
                Swal.fire("Deleted!", "Product has been deleted.", "success");
                window.location.href = "/product";
            }
        },
        error: function (error) {
            alert("An error occured." + JSON.stringify(error));
        },
    });
}

function getWarehouse(id) {
    $.ajax({
        type: "post",
        url: "edit_warehouse",
        dataType: "json",
        data: {
            id: id,
        },
        success: function (data) {
            if (data) {
                $("#warehouse_id").val(data.id);
                $("#edit_warehouse_name").val(data.name);
                $("#editWarehouseModal").modal("show");
            }
        },
        error: function (error) {
            alert("An error occured." + JSON.stringify(error));
        },
    });
}

function deleteWarehouse(id) {
    $.ajax({
        type: "post",
        url: "delete_warehouse",
        dataType: "json",
        data: {
            id: id,
        },
        success: function (data) {
            if (data) {
                Swal.fire("Deleted!", "Warehouse has been deleted.", "success");
                window.location.href = "/warehouse";
            }
        },
        error: function (error) {
            alert("An error occured." + JSON.stringify(error));
        },
    });
}

function getCustomerData(id) {
    if (id == "") {
        Swal.fire("Oops..", "Select a customer", "warning");
        $("#txtAddress").text("");
        $("#txtVatNumber").text("");
    } else {
        $.ajax({
            type: "post",
            url: "edit_user",
            dataType: "json",
            data: {
                id: id,
            },
            success: function (data) {
                if (data) {
                    $("#txtAddress").text(data.customer_address);
                    $("#txtVatNumber").text(data.customer_vat_no);
                }
            },
            error: function (error) {
                alert("An error occured." + JSON.stringify(error));
            },
        });
    }
}

function getProductData(warehouse_id) {
    var code = $("#product_id").val().trim();
    if (warehouse_id == "") {
        Swal.fire("Oops..", "Please select a warehouse.", "warning");
        $("#txtAvailbaleQty").text("");
    } else if (product_id === "") {
        Swal.fire("Oops..", "Please select a product.", "warning");
        $("#txtAvailbaleQty").text("");
    } else {
        $.ajax({
            type: "POST",
            url: "edit_product_by_code",
            dataType: "json",
            data: {
                code: code,
                warehouse_id: warehouse_id,
            },
            success: function (data) {
                if (data.success === 1) {
                    $("#txtAvailbaleQty").text(
                        "Availability: " + data.data.product.stock_available
                    );
                    calculateTotals();
                } else {
                    Swal.fire("Oops..", "No stock was found.", "warning");
                    $("#txtAvailbaleQty").text("");
                }
            },
            error: function (error) {
                Swal.fire(
                    "Oops..",
                    "An error occurred: " + error.responseJSON.message,
                    "error"
                );
            },
        });
    }
}

$("#qty").on("change", function () {
    const qty = $(this).val();
    var product_id = $("#product_id").val().trim();
    var product_name = $("#product_id option:selected").text().trim();
    var unit_price = $("#unit_price").val().trim();
    var warehouse_id = $("#warehouse_id").val().trim();
    var warehouse_name = $("#warehouse_id option:selected").text().trim();
    const availabil_qty = $("#txtAvailbaleQty").text().trim().split(":");

    var productExists = false;
    $("#invoice_table tbody tr").each(function () {
        var existingProductId = $(this).closest("tr").find("td:eq(1)").text();
        if (existingProductId == product_id) {
            productExists = true;
            return false;
        }
    });

    if (product_id == "") {
        Swal.fire("Oops..", "Select a product.", "warning");
        $(this).val("");
        return;
    } else if (warehouse_id == "") {
        Swal.fire("Oops..", "Select a warehouse.", "warning");
        $(this).val("");
        return;
    } else if (qty < 0) {
        Swal.fire("Oops..", "Quantity cannot be negative.", "warning");
        $(this).val("");
    } else if (parseFloat(availabil_qty[1]) < parseFloat(qty)) {
        Swal.fire("Oops..", "Not enough stock to isssue.", "warning");
        return;
    } else if (productExists) {
        Swal.fire("Oops..", "Product already added to the table.", "warning");
        return;
    } else {
        var productExists = false;
        $("#invoice_table tbody tr").each(function () {
            var existingProductId = $(this)
                .closest("tr")
                .find("td:eq(1)")
                .text();
            if (existingProductId == product_id) {
                productExists = true;
                return false;
            }
        });

        if (product_id == "") {
            Swal.fire("Oops..", "Select a product.", "warning");
            return;
        } else if (warehouse_id == "") {
            Swal.fire("Oops..", "Select a warehouse.", "warning");
            return;
        } else if (productExists) {
            Swal.fire(
                "Oops..",
                "Product already added to the table.",
                "warning"
            );
            return;
        } else {
            var subtotal = parseFloat(unit_price) * parseFloat(qty);
            var rowCount = $("#invoice_table tbody tr").length + 1;

            var newRow = $("<tr>");
            newRow.append("<td>" + rowCount + "</td>");
            newRow.append("<td style='display:none;'>" + product_id + "</td>");
            newRow.append(
                "<td style='display:none;'>" + warehouse_id + "</td>"
            );
            newRow.append("<td>" + warehouse_name + "</td>");
            newRow.append("<td>" + product_name + "</td>");
            newRow.append("<td class='text-center'>" + qty + "</td>");
            newRow.append("<td class='text-center'>" + unit_price + "</td>");
            newRow.append(
                "<td class='text-center'>" + subtotal.toFixed(2) + "</td>"
            );
            newRow.append(
                '<td class="text-center"><button type="button" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-trash"></i></button></td>'
            );
            $("#invoice_table tbody").append(newRow);
            $(this).val("");
            calculateTotals();
        }
    }
});

$("#qty").on("keydown", function () {
    var product_id = $("#product_id").val().trim();
    var productExists = false;
    $("#invoice_table tbody tr").each(function () {
        var existingProductId = $(this).closest("tr").find("td:eq(1)").text();
        if (existingProductId == product_id) {
            productExists = true;
            return false;
        }
    });

    if (productExists) {
        Swal.fire("Oops..", "Product already in the table.", "warning");
        return;
    }
});

function getInvoiceItems(id) {
    $.ajax({
        type: "post",
        url: "invoice_items",
        dataType: "json",
        data: {
            id: id,
        },
        success: function (data) {
            if (data.data != null) {
                $("#invoice_item_table tbody").empty();
                var items = data.data;

                for (let index = 0; index < items.length; index++) {
                    var newRow = $("<tr>");
                    newRow.append("<td>" + (index + 1) + "</td>");
                    newRow.append(
                        "<td>" + items[index].warehouse_name + "</td>"
                    );
                    newRow.append("<td>" + items[index].product_name + "</td>");
                    newRow.append(
                        "<td class='text-center'>" +
                            items[index].qty +
                            "(" +
                            items[index].product_unit +
                            ")</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            items[index].unit_price +
                            "</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            items[index].sub_total.toFixed(2) +
                            "</td>"
                    );

                    $("#invoice_item_table tbody").append(newRow);
                }

                $("#showInvoiceItemModal").modal("show");
            }
        },
        error: function (error) {
            alert("An error occured." + JSON.stringify(error));
        },
    });
}

function getIssueNotes(invoice_id) {
    $.ajax({
        type: "post",
        url: "issue_notes_for_report",
        dataType: "json",
        data: {
            invoice_id: invoice_id,
        },
        success: function (data) {
            if (data.issue_note != null) {
                $("#invoice_item_table tbody").empty();
                $("#balance_note_item_table tbody").empty();
                var issue_note = data.issue_note;
                var balance_note = data.balance_note;
                var issue_note_items = data.issue_note_items;
                var balance_note_items = data.balance_note_items;

                $("#issue_note_no").text(issue_note.issue_note_no);
                $("#invoice_no").text(issue_note.invoice_no);
                $("#customer_name").text(issue_note.customer_name);
                $("#created_by").text(issue_note.created_user);
                $("#issue_status").text(
                    issue_note.is_active == 0
                        ? "Pending"
                        : issue_note.is_active == 1
                        ? "Issued"
                        : issue_note.is_active == 2
                        ? "Rejected"
                        : issue_note.is_active == 3
                        ? "Partially Issued"
                        : "Deleted"
                );
                $("#created_at").text(issue_note.formatted_created_at);

                for (let index = 0; index < issue_note_items.length; index++) {
                    var newRow = $("<tr>");
                    newRow.append("<td>" + (index + 1) + "</td>");
                    newRow.append(
                        "<td>" + issue_note_items[index].name + "</td>"
                    );
                    newRow.append(
                        "<td>" + issue_note_items[index].stock_no + "</td>"
                    );
                    newRow.append(
                        "<td>" + issue_note_items[index].description + "</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            issue_note_items[index].order_qty +
                            " (" +
                            issue_note_items[index].unit_of_measure +
                            ")</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            issue_note_items[index].issued_qty +
                            "</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            issue_note_items[index].balance_qty +
                            "</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            issue_note_items[index].stock_available +
                            "</td>"
                    );

                    $("#invoice_item_table tbody").append(newRow);
                }

                for (
                    let index = 0;
                    index < balance_note_items.length;
                    index++
                ) {
                    var newRow = $("<tr>");
                    newRow.append("<td>" + (index + 1) + "</td>");
                    newRow.append(
                        "<td>" +
                            balance_note_items[index].issue_note_no +
                            "</td>"
                    );
                    newRow.append(
                        "<td>" +
                            balance_note_items[index].created_user +
                            "</td>"
                    );
                    newRow.append(
                        "<td>" +
                            balance_note_items[index].description +
                            " - (" +
                            balance_note_items[index].stock_no +
                            ")</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            balance_note_items[index].order_qty +
                            " (" +
                            balance_note_items[index].unit_of_measure +
                            ")</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            balance_note_items[index].issued_qty +
                            "</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            balance_note_items[index].balance_qty +
                            "</td>"
                    );

                    $("#balance_note_item_table tbody").append(newRow);
                }

                $("#showInvoiceItemModal").modal("show");
            } else {
                Swal.fire("Oops!", "Items not found..", "warning");
            }
        },
        error: function (error) {
            alert("An error occured." + JSON.stringify(error));
        },
    });
}

function getIssueNoteItems(warehouse_id, issue_note_id) {
    $.ajax({
        type: "post",
        url: "issue_note_items",
        dataType: "json",
        data: {
            warehouse_id: warehouse_id,
            issue_note_id: issue_note_id,
        },
        success: function (data) {
            if (data.issue_note_items.length > 0) {
                $("#invoice_item_table tbody").empty();
                var issue_note_items = data.issue_note_items;
                var issue_note = data.issue_note;

                for (let index = 0; index < issue_note_items.length; index++) {
                    if (issue_note_items[index].issued_qty > 0) {
                        $("#issueItems").prop("disabled", true);
                    } else {
                        $("#issueItems").prop("disabled", false);
                    }

                    var newRow = $("<tr>");
                    newRow.append("<td>" + (index + 1) + "</td>");
                    newRow.append(
                        "<td style='display: none'>" +
                            issue_note_items[index].warehouse_id +
                            "</td>"
                    );
                    newRow.append(
                        "<td>" +
                            issue_note_items[index].warehouse_name +
                            "</td>"
                    );
                    newRow.append(
                        "<td>" + issue_note_items[index].product_code + "</td>"
                    );
                    newRow.append(
                        "<td>" + issue_note_items[index].description + "</td>"
                    );
                    newRow.append(
                        "<td class='text-center'><div class='input-group mb-2'><input type='text' class='form-control' id='inlineFormInputGroup' value=" +
                            issue_note_items[index].order_qty +
                            "><div class='input-group-prepend'><div class='input-group-text'>" +
                            issue_note_items[index].unit_of_measure +
                            "</div></div></div></td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            issue_note_items[index].balance_qty +
                            "</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            issue_note_items[index].stock_available +
                            "</td>"
                    );

                    $("#invoice_item_table tbody").append(newRow);
                }

                $("#issue_note_no").text(issue_note.issue_note_no);
                $("#invoice_no").text(issue_note.invoice_no);
                $("#customer_name").text(issue_note.customer_name);
                $("#created_by").text(issue_note.created_user);
                $("#issue_status").text(
                    issue_note.is_active == 0
                        ? "Pending"
                        : issue_note.is_active == 1
                        ? "Issued"
                        : issue_note.is_active == 2
                        ? "Rejected"
                        : issue_note.is_active == 3
                        ? "Partially Issued"
                        : "Deleted"
                );
                $("#created_at").text(issue_note.formatted_created_at);

                $("#showIssueNoteItemModal").modal("show");
            } else {
                Swal.fire("Oops!", "Items not found..", "warning");
            }
        },
        error: function (error) {
            alert("An error occured." + JSON.stringify(error));
        },
    });
}

$("#issueItems").click(function () {
    var invoiceData = [];

    var invoiceData = {
        issue_note_no: $("#issue_note_no").text().trim(),
        items: [],
    };

    $("#invoice_item_table tbody tr").each(function (index) {
        var qty = $(this).closest("tr").find("td:eq(5)").find("input").val();
        var item = {
            product_code: $(this).closest("tr").find("td:eq(3)").text(),
            ordered_qty: qty,
            available_qty: $(this).closest("tr").find("td:eq(6)").text(),
        };
        invoiceData.items.push(item);
    });

    $.ajax({
        url: "/submit_issue_items",
        method: "POST",
        dataType: "json",
        contentType: "application/json",
        data: JSON.stringify(invoiceData),
        success: function (data) {
            if (data.success == 1) {
                Swal.fire(
                    "Updated!",
                    "Issue Note Items submitted successfully.",
                    "success"
                );
                getIssueNoteItems(
                    data.issue_note_item_data.warehouse_id,
                    data.issue_note_item_data.issue_note_id
                );
                $("#issueItems").prop("disabled", true);
            } else {
                Swal.fire(
                    "Oops!",
                    "Issue Note Items not submitted!.",
                    "warning"
                );
            }
        },
        error: function (xhr, status, error) {
            Swal.fire("Oops!", xhr.responseText, "error");
        },
    });
});

function getBalanceNoteItems(warehouse_id, issue_note_id) {
    $.ajax({
        type: "post",
        url: "balance_note_items",
        dataType: "json",
        data: {
            warehouse_id: warehouse_id,
            issue_note_id: issue_note_id,
        },
        success: function (data) {
            if (data.issue_note_items.length > 0) {
                $("#balance_note_item_table tbody").empty();
                var issue_note_items = data.issue_note_items;
                var issue_note = data.issue_note;
                var warehouses = data.warehouses;

                var options = [];
                for (var i = 0; i < warehouses.length; i++) {
                    options.push({
                        value: warehouses[i].id,
                        text: warehouses[i].name,
                    });
                }

                for (let index = 0; index < issue_note_items.length; index++) {
                    if (issue_note_items[index].issued_qty > 0) {
                        $("#issueItems").prop("disabled", true);
                    } else {
                        $("#issueItems").prop("disabled", false);
                    }

                    // Create select tag
                    var select = $("<select class='form-control'></select>");
                    options.forEach(function (option) {
                        var optionTag = $("<option></option>")
                            .attr("value", option.value)
                            .text(option.text);

                        // Set the selected attribute if it matches the selectedWarehouseId
                        if (
                            option.value ===
                            issue_note_items[index].warehouse_id
                        ) {
                            optionTag.attr("selected", "selected");
                        }

                        select.append(optionTag);
                    });

                    var newRow = $("<tr>");
                    newRow.append("<td>" + (index + 1) + "</td>");

                    // Append select tag to a table cell
                    var selectCell = $("<td></td>").append(select);
                    newRow.append(selectCell);

                    newRow.append(
                        "<td>" +
                            issue_note_items[index].description +
                            " - " +
                            issue_note_items[index].product_code +
                            "</td>"
                    );
                    newRow.append(
                        "<td class='text-center'><div class='input-group mb-2'><input type='text' class='form-control' id='inlineFormInputGroup' value=" +
                            issue_note_items[index].order_qty +
                            "><div class='input-group-prepend'><div class='input-group-text'>" +
                            issue_note_items[index].unit_of_measure +
                            "</div></div></div></td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            issue_note_items[index].balance_qty +
                            "</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            issue_note_items[index].stock_available +
                            "</td>"
                    );

                    $("#balance_note_item_table tbody").append(newRow);
                }

                $("#issue_note_no").text(issue_note.issue_note_no);
                $("#invoice_no").text(issue_note.invoice_no);
                $("#customer_name").text(issue_note.customer_name);
                $("#created_by").text(issue_note.created_user);
                $("#issue_status").text(
                    issue_note.is_active == 0
                        ? "Pending"
                        : issue_note.is_active == 1
                        ? "Issued"
                        : issue_note.is_active == 2
                        ? "Rejected"
                        : issue_note.is_active == 3
                        ? "Partially Issued"
                        : "Deleted"
                );
                $("#created_at").text(issue_note.formatted_created_at);

                $("#showIssueNoteItemModal").modal("show");
            } else {
                Swal.fire("Oops!", "Items not found..", "success");
            }
        },
        error: function (error) {
            alert("An error occured." + JSON.stringify(error));
        },
    });
}

$("#balanceItems").click(function () {
    var invoiceData = [];

    var invoiceData = {
        issue_note_no: $("#issue_note_no").text().trim(),
        items: [],
    };

    $("#balance_note_item_table tbody tr").each(function (index) {
        var warehouse = $(this)
            .closest("tr")
            .find("td:eq(1)")
            .find("select")
            .val();
        var code = $(this).closest("tr").find("td:eq(2)").text().split("-");
        var qty = $(this).closest("tr").find("td:eq(3)").find("input").val();
        var item = {
            warehouse_id: warehouse,
            product_code: code[1],
            ordered_qty: qty,
            available_qty: $(this).closest("tr").find("td:eq(4)").text(),
        };
        invoiceData.items.push(item);
    });

    $.ajax({
        url: "/submit_balance_items",
        method: "POST",
        dataType: "json",
        contentType: "application/json",
        data: JSON.stringify(invoiceData),
        success: function (data) {
            if (data.success == 1) {
                Swal.fire(
                    "Updated!",
                    "Issue Note Items submitted successfully.",
                    "success"
                );
                getIssueNoteItems(
                    data.issue_note_item_data.warehouse_id,
                    data.issue_note_item_data.issue_note_id
                );
                $("#issueItems").prop("disabled", true);
            } else {
                Swal.fire(
                    "Oops!",
                    "Issue Note Items not submitted!.",
                    "warning"
                );
            }
        },
        error: function (xhr, status, error) {
            Swal.fire("Oops!", xhr.responseText, "error");
        },
    });
});

$("#printItems").click(function () {
    print_content(2, $("#issue_note_no").text().trim());
});

$("#invoice_table").on("click", ".btn-remove", function () {
    $(this).closest("tr").remove();
});

function calculateTotals() {
    var subTotal = 0;
    $("#invoice_table tbody tr").each(function (index) {
        subTotal +=
            parseFloat($(this).closest("tr").find("td:eq(6)").text()) *
            parseFloat($(this).closest("tr").find("td:eq(5)").text());
    });

    const vatRate = 0.18;
    const vatAmount = subTotal * vatRate;
    const grandTotal = subTotal + vatAmount;

    $("#sub_total").text(subTotal.toFixed(2));
    $("#vat_amount").text(vatAmount.toFixed(2));
    $("#grand_total").text(grandTotal.toFixed(2));
}

$("#btn_invoice").click(function () {
    var invoiceData = {
        warehouse_id: $("#warehouse_id").val().trim(),
        customer_name:
            $("#customer_id").val() != ""
                ? $("#customer_id option:selected").text()
                : "",
        customer_address: $("#txtAddress").text().trim(),
        vat_no: $("#txtVatNumber").text().trim(),
        vat_amount: $("#vat_amount").text().trim(),
        grand_total: $("#grand_total").text().trim(),
        items: [],
    };

    $("#invoice_table tbody tr").each(function (index) {
        var item = {
            product_code: $(this).closest("tr").find("td:eq(1)").text(),
            warehouse_id: $(this).closest("tr").find("td:eq(2)").text(),
            product_name: $(this).closest("tr").find("td:eq(4)").text(),
            qty: $(this).closest("tr").find("td:eq(5)").text(),
            unit_price: $(this).closest("tr").find("td:eq(6)").text(),
            sub_total:
                parseFloat($(this).closest("tr").find("td:eq(6)").text()) *
                parseFloat($(this).closest("tr").find("td:eq(5)").text()),
        };

        invoiceData.items.push(item);
    });

    $.ajax({
        url: "/create_invoice",
        method: "POST",
        dataType: "json",
        data: invoiceData,
        success: function (data) {
            if (data.success == 1) {
                Swal.fire("Saved!", "Invoice created successfully.", "success");
                $("#customer_id").val("");
                $("#product_id").val("");
                $("#unit").text("");
                $("#unit_price").text("");
                $("#warehouse_id").val("");
                $("#qty").text("");
                $("#invoice_table tbody tr").empty();
                $("#sub_total").text("0.00");
                $("#vat_amount").text("0.00");
                $("#grand_total").text("0.00");

                if (
                    data.data.last_invoice != null &&
                    data.data.last_invoice_items.length > 0
                ) {
                    $("#invoice_id").html(data.data.last_invoice.invoice_no);
                    $("#invoice_customer_name").text(
                        data.data.last_invoice.customer_name
                    );
                    $("#invoice_customer_address").text(
                        data.data.last_invoice.customer_address
                    );
                    $("#invoice_customer_vat_no").text(
                        data.data.last_invoice.vat_no
                    );
                    $("#invoice_date").text(
                        data.data.last_invoice.invoice_date
                    );
                    $("#invoice_sale_user").text(
                        data.data.last_invoice.user_name
                    );

                    var items = data.data.last_invoice_items;
                    var subTot = 0;
                    $("#invoice_printable_table tbody").empty();
                    for (let index = 0; index < items.length; index++) {
                        var newRow = $("<tr>");
                        newRow.append("<td>" + (index + 1) + "</td>");
                        newRow.append(
                            "<td>" + items[index].warehouse_name + "</td>"
                        );
                        newRow.append(
                            "<td>" + items[index].product_name + "</td>"
                        );
                        newRow.append(
                            "<td class='text-center'>" +
                                items[index].qty +
                                "</td>"
                        );
                        newRow.append(
                            "<td class='text-center'>" +
                                items[index].unit_price +
                                "</td>"
                        );
                        newRow.append(
                            "<td class='text-center'>" +
                                items[index].sub_total.toFixed(2) +
                                "</td>"
                        );
                        subTot += items[index].sub_total;
                        $("#invoice_printable_table tbody").append(newRow);
                    }

                    $("#invoice_sub_total").text(subTot.toFixed(2));
                    $("#invoice_vat").text(
                        data.data.last_invoice.vat_amount.toFixed(2)
                    );
                    $("#invoice_grand_total").text(
                        data.data.last_invoice.grand_total.toFixed(2)
                    );
                    print_content(1, data.data.last_invoice.invoice_no);
                }
            } else {
                Swal.fire("Oops!", "Invoice not saved!.", "warning");
            }
        },
        error: function (xhr, status, error) {
            Swal.fire("Oops!", xhr.responseText, "error");
        },
    });
});

function print_content(print_type, code_number) {
    // var printContents = document.getElementById("print_content").innerHTML;
    // var originalContents = document.body.innerHTML;
    // document.body.innerHTML = printContents;
    // window.onafterprint = function () {
    //     document.body.innerHTML = originalContents;
    //     window.location.reload();
    // };
    // window.print();
    var element = document.getElementById("print_content");

    // Use html2pdf to generate and download the PDF
    var opt = {
        margin: 1,
        filename:
            print_type == 1
                ? "Invoice_" + code_number + ".pdf"
                : "Issue_Note_" + code_number + ".pdf",
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "in", format: "letter", orientation: "portrait" },
    };

    // New Promise-based usage:
    html2pdf()
        .set(opt)
        .from(element)
        .save()
        .then(() => {
            // Optional: Reload the page or reset the content after saving
            window.location.reload();
        });
}

function drawChart($data) {
    if (!$data || $data.length <= 1) {
        $("#piechart").html(
            "<div class='alert alert-danger text-center'>No chart data found</div>"
        );
    } else {
        var data = google.visualization.arrayToDataTable($data);

        var options = {
            title: "Sales Metrics",
            curveType: "function",
            legend: { position: "bottom" },
            hAxis: { title: "Date" },
            vAxis: { title: "Value" },
        };
        var chart = new google.visualization.LineChart(
            document.getElementById("piechart")
        );

        chart.draw(data, options);
    }
}

function getDeliveryNoteItems(warehouse_id, issue_note_id) {
    $.ajax({
        type: "post",
        url: "issue_note_items",
        dataType: "json",
        data: {
            warehouse_id: warehouse_id,
            issue_note_id: issue_note_id,
        },
        success: function (data) {
            if (data.issue_note_items.length > 0) {
                $("#invoice_item_table tbody").empty();
                var issue_note_items = data.issue_note_items;
                var issue_note = data.issue_note;

                for (let index = 0; index < issue_note_items.length; index++) {
                    if (issue_note_items[index].issued_qty > 0) {
                        $("#issueItems").prop("disabled", true);
                    } else {
                        $("#issueItems").prop("disabled", false);
                    }

                    var newRow = $("<tr>");
                    newRow.append("<td>" + (index + 1) + "</td>");
                    newRow.append(
                        "<td style='display: none'>" +
                            issue_note_items[index].warehouse_id +
                            "</td>"
                    );
                    newRow.append(
                        "<td>" +
                            issue_note_items[index].warehouse_name +
                            "</td>"
                    );
                    newRow.append(
                        "<td>" + issue_note_items[index].product_code + "</td>"
                    );
                    newRow.append(
                        "<td>" + issue_note_items[index].description + "</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            issue_note_items[index].order_qty +
                            "(" +
                            issue_note_items[index].unit_of_measure +
                            ")</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            issue_note_items[index].balance_qty +
                            "</td>"
                    );
                    newRow.append(
                        "<td class='text-center'>" +
                            issue_note_items[index].stock_available +
                            "</td>"
                    );

                    $("#invoice_item_table tbody").append(newRow);
                }

                $("#issue_note_no").text(issue_note.issue_note_no);
                $("#invoice_no").text(issue_note.invoice_no);
                $("#customer_name").text(issue_note.customer_name);
                $("#created_by").text(issue_note.created_user);
                $("#issue_status").text(
                    issue_note.is_active == 0
                        ? "Pending"
                        : issue_note.is_active == 1
                        ? "Issued"
                        : issue_note.is_active == 2
                        ? "Rejected"
                        : issue_note.is_active == 3
                        ? "Partially Issued"
                        : "Deleted"
                );
                $("#created_at").text(issue_note.formatted_created_at);

                $("#showIssueNoteItemModal").modal("show");
            } else {
                Swal.fire("Oops!", "Items not found..", "warning");
            }
        },
        error: function (error) {
            alert("An error occured." + JSON.stringify(error));
        },
    });
}

$("#btn_porder").click(function () {
    var invoiceData = {
        warehouse_id: $("#warehouse_id").val().trim(),
        supplier_name:
            $("#supplier_id").val() != ""
                ? $("#supplier_id option:selected").text()
                : "",
        supplier_address: $("#txtAddress").text().trim(),
        grand_total: $("#grand_total").text().trim(),
        items: [],
    };

    $("#invoice_table tbody tr").each(function (index) {
        var item = {
            product_code: $(this).closest("tr").find("td:eq(1)").text(),
            warehouse_id: $(this).closest("tr").find("td:eq(2)").text(),
            product_name: $(this).closest("tr").find("td:eq(4)").text(),
            qty: $(this).closest("tr").find("td:eq(5)").text(),
            unit_price: $(this).closest("tr").find("td:eq(6)").text(),
            sub_total:
                parseFloat($(this).closest("tr").find("td:eq(6)").text()) *
                parseFloat($(this).closest("tr").find("td:eq(5)").text()),
        };

        invoiceData.items.push(item);
    });

    $.ajax({
        url: "/create_purchase_order",
        method: "POST",
        dataType: "json",
        data: invoiceData,
        success: function (data) {
            if (data.success == 1) {
                Swal.fire(
                    "Saved!",
                    "Purchase order created successfully.",
                    "success"
                );
                $("#supplier_id").val("");
                $("#product_id").val("");
                $("#unit").text("");
                $("#unit_price").text("");
                $("#warehouse_id").val("");
                $("#qty").text("");
                $("#invoice_table tbody tr").empty();
                $("#sub_total").text("0.00");
                $("#vat_amount").text("0.00");
                $("#grand_total").text("0.00");

                if (
                    data.data.last_po != null &&
                    data.data.last_po_item.length > 0
                ) {
                    $("#invoice_id").html(data.data.last_po.invoice_no);
                    $("#invoice_customer_name").text(
                        data.data.last_po.customer_name
                    );
                    $("#invoice_customer_address").text(
                        data.data.last_po.customer_address
                    );
                    $("#invoice_customer_vat_no").text(
                        data.data.last_po.vat_no
                    );
                    $("#invoice_date").text(data.data.last_po.invoice_date);
                    $("#invoice_sale_user").text(data.data.last_po.user_name);

                    var items = data.data.last_po_item;
                    var subTot = 0;
                    $("#invoice_printable_table tbody").empty();
                    for (let index = 0; index < items.length; index++) {
                        var newRow = $("<tr>");
                        newRow.append("<td>" + (index + 1) + "</td>");
                        newRow.append(
                            "<td>" + items[index].warehouse_name + "</td>"
                        );
                        newRow.append(
                            "<td>" + items[index].product_name + "</td>"
                        );
                        newRow.append(
                            "<td class='text-center'>" +
                                items[index].qty +
                                "</td>"
                        );
                        newRow.append(
                            "<td class='text-center'>" +
                                items[index].unit_price +
                                "</td>"
                        );
                        newRow.append(
                            "<td class='text-center'>" +
                                items[index].sub_total.toFixed(2) +
                                "</td>"
                        );
                        subTot += items[index].sub_total;
                        $("#invoice_printable_table tbody").append(newRow);
                    }

                    $("#invoice_sub_total").text(subTot.toFixed(2));
                    $("#invoice_grand_total").text(
                        data.data.last_po.grand_total.toFixed(2)
                    );
                    print_content(1, data.data.last_po.purchase_order_no);
                }
            } else {
                Swal.fire("Oops!", "Purchase order not saved!.", "warning");
            }
        },
        error: function (xhr, status, error) {
            Swal.fire("Oops!", xhr.responseText, "error");
        },
    });
});
