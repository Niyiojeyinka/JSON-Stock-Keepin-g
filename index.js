setUpAddForm(); //default
populateTableRow();

function formChooser(inputName) {
  return document.forms["entryform"][inputName];
}

function onEdit() {
  //disable the btn
  formChooser("submit").setAttribute("disabled", "1");

  var data = {
    quantity: formChooser("quantity").value,
    price: formChooser("price").value,
    productname: formChooser("productname").value,
  };
  sendPostRequest(
    "./route.php/updateProduct/" + state.editIndex,
    data,
    function (data) {
      console.log(data);
      emptyInputs();
      formChooser("submit").removeAttribute("disabled");
      populateTableRow();
      alert("Edited Successfuly.....");
      setUpAddForm();
    }
  );
}
function onAdd() {
  //disable the btn
  formChooser("submit").setAttribute("disabled", "1");

  var data = {
    quantity: formChooser("quantity").value,
    price: formChooser("price").value,
    productname: formChooser("productname").value,
  };
  sendPostRequest("./route.php/saveProduct", data, function (data) {
    console.log(data);
    emptyInputs();
    formChooser("submit").removeAttribute("disabled");
    populateTableRow();
    alert("Added Successfuly.....");
  });
}

function emptyInputs() {
  formChooser("quantity").value = null;
  formChooser("price").value = null;
  formChooser("productname").value = null;
}
function setUpEditForm() {
  formChooser("quantity").value = state.stock.quantity;
  formChooser("price").value = state.stock.price;
  formChooser("productname").value = state.stock.productName;
  formChooser("submit").innerText = "Edit Entry";
  formChooser("submit").removeEventListener("click", onAdd);
  formChooser("submit").addEventListener("click", onEdit);
}
function setUpAddForm() {
  emptyInputs();
  formChooser("submit").innerText = "Add Entry";
  formChooser("submit").removeEventListener("click", onEdit);
  formChooser("submit").addEventListener("click", onAdd);
}

function editEntry(index) {
  state.stock = state.data[index];
  state.editIndex = index;
  //get the element with the index in the state
  setUpEditForm();
  window.scrollTo(0, 0);
}

function deleteEntry(index) {
  var sure = confirm("Arey you sure to delete this entry?");
  if (sure) {
    sendGetRequest("./route.php/deleteProduct/" + index, function (data) {
      populateTableRow();
      alert("Entry Delete Successfuly.....");
    });
  } else {
    alert("Operation cancelled....");
  }
}

function createRow(no, datetime, name, quantity, price, totalValue) {
  var d = new Date(datetime * 1000);
  datetime =
    d.toDateString() +
    " " +
    d.getHours() +
    ":" +
    d.getMinutes() +
    ":" +
    d.getSeconds();
  return `<tr>
              <td>${no}</td>
              <td>${datetime}</td>
              <td>${name}</td>
              <td>${quantity}</td>
              <td>${price}</td>
              <td>${totalValue}</td>
              <td><i class="fa fa-edit text-warning" style="cursor:pointer" onClick="editEntry(${
                no - 1
              })"></i></td>
              <td><i class="fa fa-trash text-danger" style="cursor:pointer" onClick="deleteEntry(${
                no - 1
              })"></i></td>
            </tr>`;
}
function populateTableRow() {
  sendGetRequest("./route.php/products", function (data) {
    //update state
    state.data = data;
    var template = data.map(function (element, index) {
      return createRow(
        index + 1,
        element.created_at,
        element.productName,
        element.quantity,
        element.price,
        element.totalValue
      );
    });
    document.getElementsByTagName("tbody")[0].innerHTML = template;
    //.appendChild;
  });
}
