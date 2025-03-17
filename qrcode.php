function generateQRCode(qrCodeData) {
    // Generate the QR code image URL
    const qrCodeUrl = https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(qrCodeData)}&size=200x200;

    // Set the QR code image source to the generated URL
    document.getElementById("qrImage").src = qrCodeUrl;

    // Set the QR code data (the characters) under the image
    document.getElementById("qrText").textContent = qrCodeData;

    // Display the modal
    document.getElementById("qrModal").style.display = "flex";
}