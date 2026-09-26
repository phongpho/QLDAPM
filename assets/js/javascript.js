document.addEventListener("DOMContentLoaded", function() {
    const giaidauSelect = document.getElementById("giaidauSelect");
    const loadingNotice = document.getElementById("loadingNotice");
    const teamListContent = document.getElementById("teamListContent");

    giaidauSelect.addEventListener("change", function() {
        const giaidauId = this.value;

        // Bật xoay spinner loading
        loadingNotice.classList.remove("d-none");
        teamListContent.classList.add("d-none");

        // Gọi AJAX lấy danh sách đội bóng mới (rỗng hoặc > 0)
        fetch(`index.php?action=laydoibong&giaidauid=${giaidauId}`)
            .then(response => response.json())
            .then(data => {
                loadingNotice.classList.add("d-none");
                teamListContent.classList.remove("d-none");

                if (data.length === 0) {
                    teamListContent.innerHTML = '<div class="text-muted small p-2 text-center">Không có đội bóng nào trong giải này.</div>';
                    return;
                }

                let html = '';
                data.forEach(team => {
                    // Loại bỏ Manchester United khỏi danh sách đối thủ
                    if (team.ten.toLowerCase().includes('manchester united')) return;

                    html += `
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="teams[]" value="${team.id}" id="team_${team.id}">
                            <label class="form-check-label d-flex align-items-center gap-2" for="team_${team.id}">
                                ${team.logo ? `<img src="${team.logo}" width="20" height="20" alt="logo">` : ''}
                                <span>${team.ten}</span>
                            </label>
                        </div>
                    `;
                });

                teamListContent.innerHTML = html;
            })
            .catch(error => {
                console.error('Lỗi AJAX:', error);
                loadingNotice.classList.add("d-none");
                teamListContent.classList.remove("d-none");
                teamListContent.innerHTML = '<div class="text-danger small p-2 text-center">Lỗi khi tải dữ liệu!</div>';
            });
    });
});