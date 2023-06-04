import "../styles/product-add.scss";
import DriveFolderUploadOutlinedIcon from "@mui/icons-material/DriveFolderUploadOutlined";
import { useState } from "react";

const AddProduct = () => {
    const [file, setFile] = useState("");

    return (
        <div className="product-add">
            <div className="newContainer">
                <div className="top">
                    <h1>Add Product</h1>
                </div>
                <div className="bottom">
                    <div className="left">
                        <img
                            src={
                                file
                                    ? URL.createObjectURL(file)
                                    : "https://icon-library.com/images/no-image-icon/no-image-icon-0.jpg"
                            }
                            alt=""
                        />
                    </div>
                    <div className="right">
                        <form>
                            <div className="formInput">
                                <label htmlFor="file">
                                    Image: <DriveFolderUploadOutlinedIcon className="icon" />
                                </label>
                                <input
                                    type="file"
                                    id="file"
                                    onChange={(e) => setFile(e.target.files[0])}
                                    style={{ display: "none" }}
                                />
                            </div>

                            <div className="formInput">
                                <label>Title</label>
                                <input type='text' placeholder='Title' />
                            </div>

                            <button>Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AddProduct;
