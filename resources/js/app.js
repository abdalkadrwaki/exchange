import $ from 'jquery';
import 'bootstrap/dist/js/bootstrap.bundle.min.js'; // هذا يحوي Bootstrap JS و Popper
import 'datatables.net-bs5';
import Swal from 'sweetalert2';

window.$ = window.jQuery = $;
window.Swal = Swal;


$(document).ready(function() {
            $('.table').DataTable({
                language: {
                    "search": "بحث:",
                    "zeroRecords": "لا توجد نتائج مطابقة",
                    "infoEmpty": "لا توجد بيانات",
                },
                responsive: true,
                dom: '<"top"f>', // فقط مربع البحث
                paging: false, // تعطيل الصفحات
                info: false, // تعطيل عرض المعلومات
                lengthChange: false // تعطيل قائمة تحديد عدد السجلات
            });



            const modal = new bootstrap.Modal(document.getElementById('editModal'));

            $('.edit-btn').click(function() {
                const userId = $(this).data('id');
                modal.show(); // Show now, don't wait

                // ثم نبدأ جلب البيانات
                $.get(`/users/edit/${userId}`, function(data) {
                    $('#user_id').val(userId);
                    $('#name').val(data.user.name);
                    $('#email').val(data.user.email);

                    $('#role').empty();
                    data.roles.forEach(role => {
                        const selected = data.user_role.includes(role.name) ? 'selected' :
                            '';
                        $('#role').append(
                            `<option value="${role.name}" ${selected}>${role.name}</option>`
                        );
                    });

                    let permsHtml = '';
                    data.permissions.forEach(p => {
                        const checked = data.user_permissions.includes(p.name) ? 'checked' :
                            '';
                        permsHtml += `
                    <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" name="permissions[]" type="checkbox" value="${p.name}" ${checked}>
                        <label class="form-check-label">${p.name}</label>
                    </div>
                </div>`;
                    });

                    $('#permissions-container').html(permsHtml);
                });
            });


            $('#editForm').submit(function(e) {
                e.preventDefault();
                const userId = $('#user_id').val();

                $.post(`/users/update/${userId}`, $(this).serialize(), function(res) {
                    if (res.success) {
                        modal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'تم التحديث',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            });

        });
