<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
    <div class="modal-content border-0 shadow">
      <form id="deleteForm" method="POST">
        <?= csrf_field() ?>
        <div class="modal-body text-center p-5">
          <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <i class="bi bi-trash" style="font-size:28px;color:#dc2626;"></i>
          </div>
          <h5 style="font-weight:700;margin-bottom:8px;">Delete?</h5>
          <p class="text-muted mb-0" style="font-size:14px;">
            Are you sure you want to delete <strong id="deleteLabel"></strong>? This action cannot be undone.
          </p>
        </div>
        <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
          <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger px-4">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
