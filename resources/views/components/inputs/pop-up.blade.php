<div id="reason-modal" class="fixed inset-0 hidden items-center justify-center bg-black/50">
    <div class="bg-white p-6 rounded w-full max-w-md">
        <h3 class="text-lg font-semibold mb-2">Reason for change</h3>
        <textarea id="reason-text" class="w-full border rounded p-2" rows="3" required></textarea>
        <div class="mt-4 flex justify-end gap-2">
            <button type="button" id="reason-cancel" class="px-3 py-1 border rounded">Cancel</button>
            <button type="button" id="reason-save" class="px-3 py-1 bg-blue-600 text-white rounded">Save</button>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('reason-modal');
    const reasonText = document.getElementById('reason-text');
    const reasonSave = document.getElementById('reason-save');
    const reasonCancel = document.getElementById('reason-cancel');
    let currentRoleId = null;

    document.querySelectorAll('[data-role-id]').forEach(btn => {
        btn.addEventListener('click', () => {
            currentRoleId = btn.dataset.roleId;
            reasonText.value = '';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            reasonText.focus();
        });
    });

    reasonCancel.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    reasonSave.addEventListener('click', () => {
        const reason = reasonText.value.trim();
        if (!reason) return;

        const input = document.getElementById(`reason-${currentRoleId}`);
        input.value = reason;
        input.closest('form').submit();
    });
</script>