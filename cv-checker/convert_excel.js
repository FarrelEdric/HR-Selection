import XLSX from "xlsx";
import fs from "fs";

const inputFile = process.argv[2];
const outputFile = process.argv[3];

if (!inputFile || !outputFile) {
    console.error("Usage: node convert_excel.js <input_file> <output_file>");
    process.exit(1);
}

try {
    const workbook = XLSX.readFile(inputFile);
    const firstSheetName = workbook.SheetNames[0];
    const worksheet = workbook.Sheets[firstSheetName];

    if (!worksheet) {
        throw new Error("No worksheet found in workbook");
    }

    // Convert to CSV with semicolon delimiter to be safe with Indonesian settings
    const csv = XLSX.utils.sheet_to_csv(worksheet, { FS: ";" });

    fs.writeFileSync(outputFile, csv);
    console.log("Conversion successful");
} catch (error) {
    console.error("Error during conversion:", error.message);
    process.exit(1);
}
