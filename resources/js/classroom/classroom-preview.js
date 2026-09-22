export function initializeClassroomPreview() {
    const previewElement = document.querySelector("#classroom-preview");

    if (!previewElement) {
        return;
    }

    function updatePreview() {
        const level = document.querySelector("#level")?.value ?? "";
        const majorOption = document.querySelector("#major option:checked");
        const major = majorOption?.dataset?.code ?? "";
        const class_number =
            document.querySelector("#class_number")?.value ?? "";

        previewElement.textContent = `Preview: ${level}-${major}-${class_number}`;
    }

    updatePreview();

    document.querySelector("#level")?.addEventListener("change", updatePreview);

    document.querySelector("#major")?.addEventListener("change", updatePreview);

    document
        .querySelector("#class_number")
        ?.addEventListener("input", updatePreview);
}
