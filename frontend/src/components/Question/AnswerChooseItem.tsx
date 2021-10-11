import React, {FC, useState} from 'react';
import {Col, Form, FormGroup, Row} from "react-bootstrap";
import {IAnswer} from "../../types/types";

interface AnswerChooseItemProps {
    answer: IAnswer;
}

const AnswerChooseItem: FC<AnswerChooseItemProps> = ({answer}) => {
    const [answerText, setAnswerText] = useState<string>('');
    const [correctAnswers, setCorrectAnswers] = useState<[]>([]);

    const changeAnswerText = (e: React.ChangeEvent<HTMLInputElement>) => {
        setAnswerText(e.target.value)
    }
    function handleCheckChange (e: React.ChangeEvent<HTMLInputElement>) {
        
    }

    return (
        <div>
            <Form>
                <Row className="align-items-center">
                    <Col xs="auto">
                        <Form.Check
                            type="checkbox"
                            checked={answer.correct}
                            className="mb-2"
                            onChange={handleCheckChange}
                        />
                    </Col>

                    <Col xs="auto">
                        <p>{answer.text}</p>
                    </Col>


                </Row>
            </Form>

        </div>
    );
};

export default AnswerChooseItem;
