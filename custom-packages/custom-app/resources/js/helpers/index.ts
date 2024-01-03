import type { UploadedFile } from "custom-app/types";

export const formatBytes = (bytes?: number, decimals?: number) => {
  if (!bytes || bytes === 0) return "0 Bytes";
  const k = 1024,
    dm = decimals || 2,
    sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"],
    i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
};

export const isFileImage = (fileName: string) => {
  return ["png", "jpg", "gif", "jpeg", "svg"].includes(
    getFileExtension(fileName)
  );
};

export const getFileExtension = (fileName: string) => {
  return fileName.split(".").pop()?.toLowerCase() ?? "";
};

export const stringToColor = (str: string) => {
  let hash = 0;

  for (let i = 0; i < str.length; i++) {
    hash = str.charCodeAt(i) + ((hash << 5) - hash);
    hash = hash & hash;
  }

  let color = "#";

  for (let i = 0; i < 3; i++) {
    let value = (hash >> (i * 8)) & 255;
    color += ("00" + value.toString(16)).slice(-2);
  }

  return color;
};

export const getMediaCollection = (
  media: Array<UploadedFile> | undefined,
  collectionName: string
) => {
  return media
    ? media.filter((item) => item.collection_name === collectionName)
    : [];
};
