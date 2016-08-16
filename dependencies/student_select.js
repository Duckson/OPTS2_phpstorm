var students = [];

$(document).ready(function () {

    $('#faculty-select').change(function () {
        if ($('#faculty-select').val() != 0) {
            $.ajax({
                url: '/OPTS2/contracts/applications/students_ajax.php',
                type: 'get',
                data: {get_groups: $('#faculty-select').val()},
                dataType: 'json',
                success: function (groups) {
                    $('#group-select').empty();
                    $('#group-select').append('<option value="0">Выберите группу</option>');
                    $.each(groups, function (num, group) {
                        $('#group-select').append('<option value="' + group.id + '">' + group.name + '</option>')
                    });
                },
                error: function (q, status_error) {
                    console.log('AJAX error: ' + status_error)
                }
            });
            $('#group-select-div').show();
        } else {
            $('#group-select-div').hide();
            $('#student-select-div').hide()
        }
    });

    $('#group-select').change(function () {
        if ($('#group-select').val() != 0) {
            $.ajax({
                url: '/OPTS2/contracts/applications/students_ajax.php',
                type: 'get',
                data: {get_students: $('#group-select').val()},
                dataType: 'json',
                success: function (students) {
                    $('#student-select').empty();
                    $('#student-select').append('<option value="0">Выберите студента</option>');
                    $.each(students, function (num, student) {
                        $('#student-select').append('<option value="' + student.id + '">' + student.name + '</option>')
                    });
                },
                error: function (q, status_error) {
                    console.log('AJAX error: ' + status_error)
                }
            });
            $('#student-select-div').show();
        } else $('#student-select-div').hide();
    });

    $('#student-button').click(function () {
        var id = $('#student-select').val();

        if (id != 0 && $.inArray(id, students) == -1) {
            $('#students-table').append(
                '<tr id="student-tr-' + id + '">' +
                '<td>' +
                $("#student-select option:selected").text() +
                '</td>' +
                '<td>' +
                $("#group-select option:selected").text() +
                '</td>' +
                '<td>' +
                '<span class="glyphicon glyphicon-remove action-glyph" onclick="deleteStudent(' + id + ')"></span>' +
                '</td>' +
                '</tr>' +
                '<input type="hidden" name="students[]" value="' + id + '">'
            );
            students[id] = id;
        }
    });
});

function deleteStudent(id) {
    $('#student-tr-' + id).remove();
    students.splice(id, 1);
}