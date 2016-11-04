// Wait for the DOM to be ready
$(function() {
  // Initialize form validation 
  $("form#lift-new").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      liftWeight: "required",
      liftReps: "required",
      /*email: {
        required: true,
        // Specify that email should be validated
        // by the built-in "email" rule
        email: true
      },
      password: {
        required: true,
        minlength: 5
      }*/
    },
    // Specify validation error messages
    messages: {
      liftWeight: "Please enter your lift weight",
      liftReps: "Please enter the amount of reps you will be doing"
      /*password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
      email: "Please enter a valid email address"*/
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
});