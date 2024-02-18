    /* Toggle Between Displays On Side Bar*/
    var mainDashboardContent = document.getElementById('mainDashboard');
    var projectTasksContent = document.getElementById('projectTasksContent');
    var myTasksContent = document.getElementById('myTasksContent');
    var teamMemberContent = document.getElementById('teamMemberContent');

        function showProjectTasks(){
            projectTasksContent.style.display = "block";
            mainDashboardContent.style.display= "none";
            myTasksContent.style.display= "none";
            teamMemberContent.style.display="none" ;
        }
        function showDashboard(){
            mainDashboardContent.style.display= "block";
            projectTasksContent.style.display = "none";
            myTasksContent.style.display= "none";
            teamMemberContent.style.display="none" ;
            

        }
        function showMyTasks() {
            myTasksContent.style.display= "block";
            mainDashboardContent.style.display= "none";
            projectTasksContent.style.display = "none";
            teamMemberContent.style.display="none" ;
            
        }
        function showTeamMembers(){
            teamMemberContent.style.display="block" ;
            mainDashboardContent.style.display= "none";
            projectTasksContent.style.display = "none";
            myTasksContent.style.display= "none";



        }
    /* End of Toggle*/
    // JavaScript to handle showing/hiding the task form pop-up
    const addTaskButton = document.getElementById("add-task-button");
    const taskFormContainer = document.getElementById("task-form-container");

    addTaskButton.addEventListener("click", () => {
        taskFormContainer.style.display = "flex"; // Show the form pop-up
    });

    taskFormContainer.addEventListener("click", (e) => {
        if (e.target === taskFormContainer) {
            taskFormContainer.style.display = "none"; // Hide the form pop-up when clicking outside the form
        }
    });

    const assignTaskButton = document.getElementById("assign-task-button");
    const assignFormContainer = document.getElementById("assign-form-container");

    assignTaskButton.addEventListener("click", () => {
        assignFormContainer.style.display = "flex"; // Show the form pop-up
    });

    assignFormContainer.addEventListener("click", (e) => {
        if (e.target === assignFormContainer) {
            assignFormContainer.style.display = "none"; // Hide the form pop-up when clicking outside the form
        }
    });
// Get all the project items
const projectItems = document.getElementsByClassName("project-item");

// Get the task info container
const taskInfo = document.getElementById("task-info-container");

// Add click event listeners to each project item
for (let item of projectItems) {
  item.addEventListener("click", () => {
    // Display the task info container
    taskInfo.style.display = "flex";
  });
}

// Close the task info when clicking outside of the task info box
taskInfo.addEventListener("click", (e) => {
  if (e.target === taskInfo) {
    taskInfo.style.display = "none";
  }
});


    
