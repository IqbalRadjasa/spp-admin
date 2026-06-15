export function initializeClassroomPreview() {
    const previewElement = document.querySelector("#classroom-preview");

    if (!previewElement) {
        return;
    }

    function updatePreview() {
        const level = document.querySelector("#level")?.value ?? "";
        const majorOption = document.querySelector("#major option:checked");
        const major = majorOption?.dataset?.code ?? "";
        const name = document.querySelector("#name")?.value ?? "";

        previewElement.textContent = `Preview: ${level}-${major}-${name}`;
    }

    updatePreview();

    document.querySelector("#level")?.addEventListener("change", updatePreview);

    document.querySelector("#major")?.addEventListener("change", updatePreview);

    document.querySelector("#name")?.addEventListener("input", updatePreview);
}
