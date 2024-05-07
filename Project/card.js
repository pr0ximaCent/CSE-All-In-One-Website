const target = {
    clicked: 0,
    currentFollowers: 90,
    btn: document.querySelector("a.btn"),
    fw: document.querySelector("span.followers")
  };
  
  const follow = () => {
    target.clicked += 1;
    target.btn.innerHTML = 'Following <i class="fas fa-user-times"></i>';
  
    if (target.clicked % 2 === 0) {
      target.currentFollowers -= 1;
      target.btn.innerHTML = 'Follow <i class="fas fa-user-plus"></i>';
    }
    else {
      target.currentFollowers += 1;
    }
  
    target.fw.textContent = target.currentFollowers;
    target.btn.classList.toggle("following");
  }
  $(document).ready(function() {
    // Change Password button click event
    $('#changePasswordBtn').click(function() {
      var newPassword = prompt('Enter new password:');
      if (newPassword) {
        $('input[name="newpassword"]').val(newPassword);
      }
    });
    
    // Change Designation button click event
    $('#changeDesignationBtn').click(function() {
      var newDesignation = prompt('Enter new designation:');
      if (newDesignation) {
        $('input[name="designation"]').val(newDesignation);
      }
    });
  });
  
  