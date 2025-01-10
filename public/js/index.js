document.addEventListener("DOMContentLoaded", () => {
  const userTableBody = document.getElementById("userTableBody");
  const createUserForm = document.getElementById("createUserForm");

  const fetchUsers = async () => {
    const response = await fetch("http://localhost:8000/user/index");
    const users = await response.json();
    userTableBody.innerHTML = users
      .map(
        (user) => `
            <tr>
                <td>${user.name}</td>
                <td>${user.lastname}</td>
                <td>${user.gender}</td>
                <td>${user.birthday}</td>
                <td>${user.state === 1 ? 'Activo' : 'Inactivo'}</td>
                <td>
                    <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editUserModal"  data-id="${user.id}">Edit</button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="${user.id}">Delete</button>
                </td>
            </tr>
        `
      )
      .join("");
  };

  // Create user
  createUserForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    const formData = new FormData(createUserForm);
    const response = await fetch("http://localhost:8000/user/create", {
      method: "POST",
      body: formData,
    });
    if (response.ok) {
      fetchUsers();
      createUserForm.reset();
      Swal.fire({
        title: "Excelente!",
        text: "Usuario registrado con exito!",
        icon: "success",
      });
      const createUserModal = new bootstrap.Modal(
        document.getElementById("createUserModal")
      );
      createUserModal.hide();
      const previousElement = document.activeElement;
      previousElement.focus();
    } else {
      alert("Error creating user");
    }
  });

  // Edit user
  userTableBody.addEventListener("click", async (event) => {
    if (event.target.classList.contains("edit-btn")) {
      const userId = event.target.getAttribute("data-id");
      const response = await fetch(`http://localhost:8000/user/show/${userId}`);
      const user = await response.json();

      if (user) {
        document.getElementById("editUserId").value = user.id;
        document.getElementById("editName").value = user.name;
        document.getElementById("editLastname").value = user.lastname;
        document.getElementById("editGender").value = user.gender;
        document.getElementById("editBirthday").value = user.birthday;
        document.getElementById("editPassword").value = user.password;
        document.getElementById("editState").value = user.state;

        const editUserModal = new bootstrap.Modal(
          document.getElementById("editUserModal")
        );
        editUserModal.show();
      }
    }
  });

  editUserForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    const userId = document.getElementById("editUserId").value;
    const formData = new FormData(editUserForm);
    const response = await fetch(`http://localhost:8000/user/edit/${userId}`, {
      method: "POST",
      body: formData,
    });

    if (response.ok) {
      fetchUsers();
      Swal.fire({
        title: "Excelente!",
        text: "Usuario editado con exito!",
        icon: "success",
      });
      const editUserModal = new bootstrap.Modal(
        document.getElementById("editUserModal")
      );
      editUserModal.hide();
    } else {
      alert("Error updating user");
    }
  });

  // Delete user
  userTableBody.addEventListener("click", async (event) => {
    if (event.target.classList.contains("delete-btn")) {
      const userId = event.target.getAttribute("data-id");
      Swal.fire({
        title: "Esta seguro de borrar este registro?",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Si",
        denyButtonText: `No,regresar`,
      }).then(async (result) => {
        if (result.isConfirmed) {
          const response = await fetch(
            `http://localhost:8000/user/delete/${userId}`,
            {
              method: "DELETE",
            }
          );

          if (response.ok) {
            Swal.fire("Eliminado!", "", "success");

            fetchUsers();
          } else {
            alert("Error deleting user");
          }
        } else if (result.isDenied) {
          Swal.fire("No se altero ningun registro", "", "info");
        }
      });
    }
  });

  fetchUsers();
});
const createUserModalElement = document.getElementById("createUserModal");
createUserModalElement.addEventListener("shown.bs.modal", function () {
  document.getElementById("name").focus();
});

createUserModalElement.addEventListener("hidden.bs.modal", function () {
  const previousElement = document.activeElement;
  previousElement.focus();
});
